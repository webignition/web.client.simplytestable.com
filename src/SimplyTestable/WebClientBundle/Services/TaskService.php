<?php

namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory as TaskOutputResultParserFactory;
use webignition\WebResource\JsonDocument\JsonDocument;

class TaskService extends CoreApplicationService
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
     * @var HttpClientService
     */
    private $httpClientService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param $parameters
     * @param WebResourceService $webResourceService
     * @param TaskOutputResultParserFactory $taskOutputResultParserFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $parameters,
        WebResourceService $webResourceService,
        TaskOutputResultParserFactory $taskOutputResultParserFactory
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->entityManager = $entityManager;
        $this->taskOutputResultParserService = $taskOutputResultParserFactory;

        $this->taskRepository = $this->entityManager->getRepository(Task::class);
        $this->taskOutputRepository = $this->entityManager->getRepository(Output::class);

        $this->httpClientService = $this->webResourceService->getHttpClientService();
    }

    /**
     * @param Test $test
     * @param array|null $remoteTaskIds
     * @return Task[]
     *
     * @throws WebResourceException
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
            $remoteTasksObject = $this->retrieveRemoteCollection($test, $tasksToRetrieve);

            $outputs = [];

            foreach ($remoteTasksObject as $remoteTaskObject) {
                if (isset($remoteTaskObject->output)) {
                    $outputs[] = $this->populateOutputFromRemoteOutputObject($remoteTaskObject);
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

            foreach ($remoteTasksObject as $remoteTaskObject) {
                $task = (isset($tasks[$remoteTaskObject->id])) ? $tasks[$remoteTaskObject->id] : new Task();
                $task->setTest($test);
                $this->populateFromRemoteTaskObject($task, $remoteTaskObject);
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
        if ($this->isIncomplete($task)) {
            $task->setState(Task::STATE_CANCELLED);
        } elseif ($this->isCancelled($task)) {
            $task->setState(Task::STATE_CANCELLED);
        } elseif ($this->isFailed($task)) {
            $task->setState(Task::STATE_FAILED);
        }
    }

    /**
     *
     * @param Task $task
     * @param array $previousTaskStates
     * @return boolean
     */
    private function hasTaskStateChanged(Task $task, $previousTaskStates) {
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
     *
     * @param Task $task
     * @param \stdClass $remoteTaskObject
     */
    private function populateFromRemoteTaskObject(Task $task, \stdClass $remoteTaskObject) {
        $propertyToMethodMap = [
            'id' => 'setTaskId',
            'url' => 'setUrl',
            'state' => 'setState',
            'worker'=> 'setWorker',
            'type' => 'setType'
        ];

        foreach ($propertyToMethodMap as $propertyName => $methodName) {
            $task->$methodName($remoteTaskObject->$propertyName);
        }

        if (isset($remoteTaskObject->time_period)) {
            $this->updateTimePeriodFromJsonObject($task->getTimePeriod(), $remoteTaskObject->time_period);
        }

        if (isset($remoteTaskObject->output)) {
            if (!$task->hasOutput()) {
                $task->setOutput($this->populateOutputFromRemoteOutputObject($remoteTaskObject));
            }
        }
    }

    /**
     * @param \stdClass $remoteTaskObject
     *
     * @return Output
     */
    private function populateOutputFromRemoteOutputObject($remoteTaskObject)
    {
        $remoteOutputObject = $remoteTaskObject->output;

        $output = new Output();
        $output->setContent($remoteOutputObject->output);
        $output->setType($remoteTaskObject->type);
        $output->setErrorCount($remoteOutputObject->error_count);
        $output->setWarningCount($remoteOutputObject->warning_count);
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
     *
     * @param TimePeriod $timePeriod
     * @param type $jsonObject
     * @return \SimplyTestable\WebClientBundle\Entity\TimePeriod
     */
    private function updateTimePeriodFromJsonObject(TimePeriod $timePeriod, $jsonObject) {
        if (isset($jsonObject->time_period)) {
            if (isset($jsonObject->time_period->start_date_time)) {
                $timePeriod->setStartDateTime(new \DateTime($jsonObject->time_period->start_date_time));
            }

            if (isset($jsonObject->time_period->end_date_time)) {
                $timePeriod->setEndDateTime(new \DateTime($jsonObject->time_period->end_date_time));
            }
        }
    }


    /**
     *
     * @param Test $test
     * @param array $remoteTaskIds
     * @return array
     */

    /**
     * @param Test $test
     * @param int[] $remoteTaskIds
     *
     * @return \stdClass
     *
     * @throws WebResourceException
     */
    private function retrieveRemoteCollection(Test $test, $remoteTaskIds)
    {
        $httpRequest = $this->httpClientService->postRequest($this->getUrl('test_tasks', [
                'canonical-url' => urlencode($test->getWebsite()),
                'test_id' => $test->getTestId()
        ]), null, [
            'taskIds' => implode(',', $remoteTaskIds)
        ]);

        $this->addAuthorisationToRequest($httpRequest);

        /* @var JsonDocument $jsonDocument */
        $jsonDocument = $this->webResourceService->get($httpRequest);

        return $jsonDocument->getContentObject();
    }

    /**
     *
     * @param Test $test
     * @return array
     */
    public function getRemoteTaskIds(Test $test) {
        if (($test->getState() == 'new' || $test->getState() == 'preparing')) {
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
     *
     * @param Test $test
     * @param int $limit
     * @return array
     */
    public function getUnretrievedRemoteTaskIds(Test $test, $limit) {
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
     *
     * @param Test $test
     * @return array
     */
    private function retrieveRemoteTaskIds(Test $test) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_task_ids', [
                'canonical-url' => urlencode($test->getWebsite()),
                'test_id' => $test->getTestId()
        ]));

        $this->addAuthorisationToRequest($httpRequest);

        return $this->webResourceService->get($httpRequest)->getContentObject();
    }

    /**
     * @param Test $test
     * @param int $task_id
     *
     * @return Task|null
     *
     * @throws WebResourceException
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
     * @param Task $task
     * @return boolean
     */
    private function isCancelled(Task $task) {
        return in_array($task->getState(), $this->cancelledStates);
    }


    /**
     *
     * @param Task $task
     * @return boolean
     */
    private function isFailed(Task $task) {
        return in_array($task->getState(), $this->failedStates);
    }

    /**
     *
     * @param Task $task
     * @return boolean
     */
    public function isIncomplete(Task $task) {
        return in_array($task->getState(), $this->incompleteStates);
    }
}
