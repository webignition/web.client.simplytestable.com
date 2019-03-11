<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Test;
use App\Entity\Task\Task;
use App\Entity\Task\Output;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Repository\TaskOutputRepository;
use App\Repository\TaskRepository;
use App\Services\Factory\TaskFactory;
use App\Services\Factory\TaskOutputFactory;
use App\Services\TaskOutput\ResultParser\Factory as TaskOutputResultParserFactory;

class TaskService
{
    /**
     * @var string[]
     */
    private $finishedStates = [
        Task::STATE_COMPLETED,
        Task::STATE_CANCELLED,
        Task::STATE_AWAITING_CANCELLATION,
        Task::STATE_FAILED_NO_RETRY_AVAILABLE,
        Task::STATE_FAILED_RETRY_AVAILABLE,
        Task::STATE_FAILED_RETRY_LIMIT_REACHED,
    ];

    /**
     * @var string[]
     */
    private $cancelledStates = [
        Task::STATE_CANCELLED,
        Task::STATE_AWAITING_CANCELLATION,
    ];

    /**
     * @var string[]
     */
    private $failedStates = [
        Task::STATE_FAILED_NO_RETRY_AVAILABLE,
        Task::STATE_FAILED_RETRY_AVAILABLE,
        Task::STATE_FAILED_RETRY_LIMIT_REACHED,
    ];

    /**
     * @var string[]
     */
    private $incompleteStates = [
        Task::STATE_QUEUED,
        Task::STATE_QUEUED_FOR_ASSIGNMENT,
        Task::STATE_IN_PROGRESS,
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    /**
     * @var TaskOutputResultParserFactory
     */
    private $taskOutputResultParserService;

    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * @var TaskFactory
     */
    private $taskFactory;

    /**
     * @var TaskOutputFactory
     */
    private $taskOutputFactory;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TaskOutputResultParserFactory $taskOutputResultParserFactory
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     * @param JsonResponseHandler $jsonResponseHandler
     * @param TaskFactory $taskFactory
     * @param TaskOutputFactory $taskOutputFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TaskOutputResultParserFactory $taskOutputResultParserFactory,
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler,
        TaskFactory $taskFactory,
        TaskOutputFactory $taskOutputFactory
    ) {
        $this->entityManager = $entityManager;
        $this->taskOutputResultParserService = $taskOutputResultParserFactory;

        $this->taskRepository = $this->entityManager->getRepository(Task::class);
        $this->taskOutputRepository = $this->entityManager->getRepository(Output::class);

        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
        $this->taskFactory = $taskFactory;
        $this->taskOutputFactory = $taskOutputFactory;
    }

    /**
     * @param Test $test
     * @param array|null $remoteTaskIds
     *
     * @return Task[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getCollection(Test $test, $remoteTaskIds = null)
    {
        if (!is_array($remoteTaskIds)) {
            $remoteTaskIds = $this->getRemoteTaskIds($test);
        }

        $existenceResult = $this->taskRepository->getCollectionExistsByTestAndRemoteId($test, $remoteTaskIds);

        $tasksToRetrieve = [];
        $localTasksToUpdate = [];

        foreach ($existenceResult as $remoteTaskId => $exists) {
            if ($exists) {
                $localTasksToUpdate[] = $remoteTaskId;
            } else {
                $tasksToRetrieve[] = $remoteTaskId;
            }
        }

        $tasksToPersist = [];
        $tasks = [];
        $previousTaskStates = [];

        if (count($localTasksToUpdate)) {
            $tasks = $this->taskRepository->getCollectionByTestAndRemoteId($test, $remoteTaskIds);
            $previousTaskStates = $this->getTaskStates($tasks);

            foreach ($tasks as $task) {
                $isFinished = in_array($task->getState(), $this->finishedStates);

                if (!$isFinished) {
                    $tasksToRetrieve[] = $task->getTaskId();
                }
            }
        }

        if (count($tasksToRetrieve)) {
            $remoteTaskDataCollection = $this->retrieveRemoteCollection($test, $tasksToRetrieve);
            $outputs = [];

            foreach ($remoteTaskDataCollection as $remoteTaskData) {
                if (array_key_exists('output', $remoteTaskData)) {
                    $output = $this->taskOutputFactory->create($remoteTaskData['type'], $remoteTaskData['output']);
                    $this->entityManager->persist($output);
                    $this->entityManager->flush();

                    $outputs[] = $output;
                }
            }

            foreach ($remoteTaskDataCollection as $remoteTaskData) {
                $remoteTaskId = $remoteTaskData['id'];

                $task = isset($tasks[$remoteTaskId])
                    ? $tasks[$remoteTaskId]
                    : $this->taskFactory->create($test);

                $this->taskFactory->hydrate($task, $remoteTaskData);
                $tasks[$task->getTaskId()] = $task;
            }
        }

        foreach ($tasks as $remoteTaskId => $task) {
            if ($this->hasTaskStateChanged($task, $previousTaskStates)) {
                $tasksToPersist[$task->getTaskId()] = $task;
            }
        }

        if (count($tasksToPersist)) {
            foreach ($tasksToPersist as $task) {
                if (!$test->getTasks()->contains($task)) {
                    $test->addTask($task);
                }

                /* @var $task Task */
                $this->entityManager->persist($task);
            }

            $this->entityManager->flush();
        }

        if ($test->getState() == Test::STATE_COMPLETED || $test->getState() == Test::STATE_CANCELLED) {
            foreach ($tasks as $task) {
                $this->normaliseEndingState($task);
            }
        }

        return $tasks;
    }

    /**
     * @param Task $task
     */
    private function normaliseEndingState(Task $task)
    {
        $isCancelled = in_array($task->getState(), $this->cancelledStates);
        $isFailed = in_array($task->getState(), $this->failedStates);

        if ($this->isIncomplete($task)) {
            $task->setState(Task::STATE_CANCELLED);
        } elseif ($isCancelled) {
            $task->setState(Task::STATE_CANCELLED);
        } elseif ($isFailed) {
            $task->setState(Task::STATE_FAILED);
        }
    }

    /**
     * @param Task $task
     * @param array $previousTaskStates
     *
     * @return bool
     */
    private function hasTaskStateChanged(Task $task, $previousTaskStates)
    {
        if (!isset($previousTaskStates[$task->getTaskId()])) {
            return true;
        }

        return $previousTaskStates[$task->getTaskId()] != $task->getState();
    }

    /**
     * @param Task[] $tasks
     *
     * @return string[]
     */
    private function getTaskStates($tasks)
    {
        $taskStates = [];

        foreach ($tasks as $task) {
            $taskStates[$task->getTaskId()] = $task->getState();
        }

        return $taskStates;
    }

    /**
     * @param Test $test
     * @param $remoteTaskIds
     *
     * @return array
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function retrieveRemoteCollection(Test $test, $remoteTaskIds)
    {
        $response = null;

        try {
            $response = $this->coreApplicationHttpClient->post(
                'test_tasks',
                [
                    'test_id' => $test->getTestId(),
                ],
                [
                    'taskIds' => implode(',', $remoteTaskIds),
                ]
            );
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            // Not a write request, can't happen
        }

        return $this->jsonResponseHandler->handle($response);
    }

    /**
     * @param Test $test
     *
     * @return int[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getRemoteTaskIds(Test $test)
    {
        if (($test->getState() == Test::STATE_STARTING || $test->getState() == Test::STATE_PREPARING)) {
            return [];
        }

        if (!$test->hasTaskIds()) {
            $test->setTaskIdCollection(implode(',', $this->retrieveRemoteTaskIds($test)));

            $this->entityManager->persist($test);
            $this->entityManager->flush();
        }

        return $test->getTaskIds();
    }

    /**
     * @param Test $test
     * @param int $limit
     *
     * @return int[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getUnretrievedRemoteTaskIds(Test $test, $limit)
    {
        $remoteTaskIds = $this->getRemoteTaskIds($test);
        $retrievedRemoteTaskIds = $this->taskRepository->findRetrievedRemoteTaskIds($test);

        $unretrievedRemoteTaskIds = [];

        foreach ($remoteTaskIds as $remoteTaskId) {
            if (!in_array($remoteTaskId, $retrievedRemoteTaskIds)) {
                $unretrievedRemoteTaskIds[] = $remoteTaskId;
            }

            if (count($unretrievedRemoteTaskIds) === $limit) {
                return $unretrievedRemoteTaskIds;
            }
        }

        return $unretrievedRemoteTaskIds;
    }

    /**
     * @param Test $test
     *
     * @return int[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function retrieveRemoteTaskIds(Test $test)
    {
        $response = $this->coreApplicationHttpClient->get(
            'test_task_ids',
            [
                'canonical_url' => $test->getWebsite(),
                'test_id' => $test->getTestId(),
            ]
        );

        return $this->jsonResponseHandler->handle($response);
    }

    /**
     * @param Test $test
     * @param int $task_id
     *
     * @return Task|null
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function get(Test $test, $task_id)
    {
        $task = $this->taskRepository->findOneBy([
            'taskId' => $task_id,
            'test' => $test
        ]);

        if (empty($task)) {
            $this->getCollection($test, [$task_id]);
        }

        $task = $this->taskRepository->findOneBy([
            'taskId' => $task_id,
            'test' => $test
        ]);

        if (empty($task)) {
            return null;
        }

        if ($test->getState() == Test::STATE_COMPLETED || $test->getState() == Test::STATE_CANCELLED) {
            $this->normaliseEndingState($task);
        }

        $this->setParsedOutput($task);

        return $task;
    }

    /**
     * @param Task $task
     */
    public function setParsedOutput(Task $task)
    {
        if ($task->hasOutput()) {
            $parser = $this->taskOutputResultParserService->getParser($task->getOutput());
            $parser->setOutput($task->getOutput());

            $task->getOutput()->setResult($parser->getResult());
        }
    }

    /**
     * @param Task[] $tasks
     */
    public function setParsedOutputOnCollection($tasks)
    {
        foreach ($tasks as $task) {
            $this->setParsedOutput($task);
        }
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function isIncomplete(Task $task)
    {
        return in_array($task->getState(), $this->incompleteStates);
    }
}
