<?php

namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory as TaskOutputResultParserFactory;

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
     * @param EntityManagerInterface $entityManager
     * @param TaskOutputResultParserFactory $taskOutputResultParserFactory
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TaskOutputResultParserFactory $taskOutputResultParserFactory,
        CoreApplicationHttpClient $coreApplicationHttpClient
    ) {
        $this->entityManager = $entityManager;
        $this->taskOutputResultParserService = $taskOutputResultParserFactory;

        $this->taskRepository = $this->entityManager->getRepository(Task::class);
        $this->taskOutputRepository = $this->entityManager->getRepository(Output::class);

        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
    }

    /**
     * @param Test $test
     * @param array|null $remoteTaskIds
     *
     * @return Task[]
     *
     * @throws CoreApplicationReadOnlyException
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
                    $outputs[] = $this->populateOutputFromRemoteTaskData($remoteTaskData);
                }
            }

            if (count($outputs)) {
                $outputs = $this->minimiseOutputCollection($outputs);

                if (count($outputs)) {
                    foreach ($outputs as $output) {
                        $this->entityManager->persist($output);
                    }

                    $this->entityManager->flush();
                };
            }

            foreach ($remoteTaskDataCollection as $remoteTaskData) {
                $remoteTaskId = $remoteTaskData['id'];

                $task = (isset($tasks[$remoteTaskId])) ? $tasks[$remoteTaskId] : new Task();
                $task->setTest($test);
                $this->populateFromRemoteTaskData($task, $remoteTaskData);
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
     * @param Output[] $outputs
     *
     * @return Output[]
     */
    private function minimiseOutputCollection($outputs)
    {
        $processedHashes = [];

        foreach ($outputs as $outputIndex => $output) {
            if (in_array($output->getHash(), $processedHashes) || $output->hasId()) {
                unset($outputs[$outputIndex]);
            } else {
                $processedHashes[] = $output->getHash();
            }
        }

        return $outputs;
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
     * @param Task $task
     *
     * @param array $remoteTaskData
     */
    private function populateFromRemoteTaskData(Task $task, array $remoteTaskData)
    {
        $task->setTaskId($remoteTaskData['id']);
        $task->setUrl($remoteTaskData['url']);
        $task->setState($remoteTaskData['state']);
        $task->setWorker($remoteTaskData['worker']);
        $task->setType($remoteTaskData['type']);

        if (array_key_exists('time_period', $remoteTaskData)) {
            $timePeriodData = $remoteTaskData['time_period'];

            if (array_key_exists('start_date_time', $timePeriodData)) {
                $task->getTimePeriod()->setStartDateTime(new \DateTime($timePeriodData['start_date_time']));
            }

            if (array_key_exists('end_date_time', $timePeriodData)) {
                $task->getTimePeriod()->setEndDateTime(new \DateTime($timePeriodData['end_date_time']));
            }
        }

        if (array_key_exists('output', $remoteTaskData)) {
            if (!$task->hasOutput()) {
                $task->setOutput($this->populateOutputFromRemoteTaskData($remoteTaskData));
            }
        }
    }

    /**
     * @param array $remoteTaskData
     *
     * @return Output
     */
    private function populateOutputFromRemoteTaskData(array $remoteTaskData)
    {
        $remoteOutputData = $remoteTaskData['output'];

        $output = new Output();
        $output->setContent($remoteOutputData['output']);
        $output->setType($remoteTaskData['type']);
        $output->setErrorCount($remoteOutputData['error_count']);
        $output->setWarningCount($remoteOutputData['warning_count']);
        $output->generateHash();

        $existingOutput = $this->taskOutputRepository->findOneBy([
            'hash' => $output->getHash(),
        ]);

        if (!empty($existingOutput)) {
            $output = $existingOutput;
        }

        return $output;
    }

    /**
     * @param Test $test
     * @param $remoteTaskIds
     *
     * @return array
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function retrieveRemoteCollection(Test $test, $remoteTaskIds)
    {
        return $this->coreApplicationHttpClient->post(
            'test_tasks',
            [
                'canonical_url' => (string)$test->getWebsite(),
                'test_id' => $test->getTestId(),
            ],
            [
                'taskIds' => implode(',', $remoteTaskIds),
            ],
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );
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
            $test->setTaskIdColletion(implode(',', $this->retrieveRemoteTaskIds($test)));

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
        return $this->coreApplicationHttpClient->get(
            'test_task_ids',
            [
                'canonical_url' => (string)$test->getWebsite(),
                'test_id' => $test->getTestId(),
            ],
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );
    }

    /**
     * @param Test $test
     * @param int $task_id
     *
     * @return Task|null
     *
     * @throws CoreApplicationReadOnlyException
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
