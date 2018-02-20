<?php
namespace SimplyTestable\WebClientBundle\Services;

use ResqueBundle\Resque\Resque;
use Psr\Log\LoggerInterface;
use ResqueBundle\Resque\Job;
use webignition\ResqueJobFactory\ResqueJobFactory;

/**
 * Wrapper for \ResqueBundle\Resque that handles exceptions
 * when trying to interact with queues.
 *
 * Exceptions generally occur when trying to establish a socket connection to
 * a redis server that does not exist. This can happen as in some environments
 * where the integration with redis is optional.
 *
 */
class ResqueQueueService
{
    const QUEUE_KEY = 'queue';

    /**
     * @var Resque
     */
    private $resque;

    /**
     * @var string
     */
    private $environment = 'prod';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResqueJobFactory
     */
    private $jobFactory;

    /**
     * @param Resque $resque
     * @param string $environment
     * @param LoggerInterface $logger
     * @param ResqueJobFactory $jobFactoryService
     */
    public function __construct(
        Resque $resque,
        LoggerInterface $logger,
        ResqueJobFactory $jobFactoryService,
        $environment = 'prod'
    ) {
        $this->resque = $resque;
        $this->logger = $logger;
        $this->jobFactory = $jobFactoryService;
        $this->environment = $environment;
    }

    /**
     * @param string $queue_name
     * @param array $args
     *
     * @return bool
     */
    public function contains($queue_name, $args = null)
    {
        try {
            return !is_null($this->findRedisValue($queue_name, $args));
        } catch (\CredisException $credisException) {
            $this->logger->warn('ResqueQueueService::enqueue: Redis error ['.$credisException->getMessage().']');
        }

        return false;
    }

    /**
     * @param string $queue
     * @param array $args
     *
     * @return string
     */
    private function findRedisValue($queue, $args)
    {
        $queueLength = $this->getQueueLength($queue);

        for ($queueIndex = 0; $queueIndex < $queueLength; $queueIndex++) {
            $jobDetails = json_decode(\Resque::redis()->lindex(self::QUEUE_KEY . ':' . $queue, $queueIndex));

            if ($this->match($jobDetails, $queue, $args)) {
                return json_encode($jobDetails);
            }
        }

        return null;
    }

    /**
     * @param string $jobDetails
     * @param string $queue
     * @param array $args
     *
     * @return bool
     */
    private function match($jobDetails, $queue, $args)
    {
        if (!isset($jobDetails->class)) {
            return false;
        }

        if ($jobDetails->class != $this->jobFactory->getJobClassName($queue)) {
            return false;
        }

        if (!isset($jobDetails->args)) {
            return false;
        }

        if (!isset($jobDetails->args[0])) {
            return false;
        }

        foreach ($args as $key => $value) {
            if (!isset($jobDetails->args[0]->$key)) {
                return false;
            }

            if ($jobDetails->args[0]->$key != $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $queue
     *
     * @return int
     */
    private function getQueueLength($queue)
    {
        return \Resque::redis()->llen(self::QUEUE_KEY . ':' . $queue);
    }

    /**
     * @param Job $job
     * @param bool $trackStatus
     *
     * @return null|\Resque_Job_Status
     *
     * @throws \CredisException
     * @throws \Exception
     */
    public function enqueue(Job $job, $trackStatus = false)
    {
        try {
            return $this->resque->enqueue($job, $trackStatus);
        } catch (\CredisException $credisException) {
            $this->logger->warn('ResqueQueueService::enqueue: Redis error ['.$credisException->getMessage().']');
        }
    }
}