<?php

namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\Common\Collections\ArrayCollection;

class RemoteTest extends AbstractStandardObject
{
    /**
     * @var string[]
     */
    private $taskFinishedStates = array(
        'cancelled',
        'completed',
        'failed',
        'skipped'
    );

    /**
     * @var ArrayCollection
     */
    private $owners = null;

    /**
     *
     * @return string
     */
    public function getState()
    {
        $state = $this->getProperty('state');
        $crawlData = $this->getCrawl();

        if (Test::STATE_FAILED_NO_SITEMAP === $state && !empty($crawlData)) {
            return Test::STATE_CRAWLING;
        }

        return $state;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getProperty('type');
    }

    /**
     * @return int|null
     */
    public function getUrlCount()
    {
        return $this->getProperty('url_count');
    }

    /**
     * @return int|null
     */
    public function getTaskCount()
    {
        $taskCount = $this->getProperty('task_count');

        return empty($taskCount) ? 0 : $taskCount;
    }

    /**
     * @return TimePeriod|null
     */
    public function getTimePeriod()
    {
        if (!$this->hasProperty('time_period')) {
            return null;
        }

        $remoteTimePeriodData = $this->getProperty('time_period');

        $timePeriod = new TimePeriod();

        if (array_key_exists('start_date_time', $remoteTimePeriodData)) {
            $timePeriod->setStartDateTime(new \DateTime($remoteTimePeriodData['start_date_time']));
        }

        if (array_key_exists('end_date_time', $remoteTimePeriodData)) {
            $timePeriod->setEndDateTime(new \DateTime($remoteTimePeriodData['end_date_time']));
        }

        return $timePeriod;
    }

    /**
     * @return array
     */
    public function getTaskTypes()
    {
        $taskTypes = [];

        foreach ($this->source['task_types'] as $taskTypeData) {
            $taskTypes[] = $taskTypeData['name'];
        }

        return $taskTypes;
    }

    /**
     * @return ParameterBag
     */
    public function getOptions()
    {
        $parameterBag = new ParameterBag();

        foreach ($this->getTaskTypes() as $taskType) {
            $parameterBag->set(strtolower(str_replace(' ', '-', $taskType)), 1);
        }

        foreach ($this->source['task_type_options'] as $taskType => $taskTypeOptions) {
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskType));

            foreach ($taskTypeOptions as $taskTypeOptionKey => $taskTypeOptionValue) {
                $parameterBag->set($taskTypeKey . '-' . $taskTypeOptionKey, $taskTypeOptionValue);
            }
        }

        return $parameterBag;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        $parameters = json_decode($this->getProperty('parameters'), true);

        if (!is_array($parameters)) {
            return null;
        }

        return array_key_exists($key, $parameters)
            ? $parameters[$key]
            : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasParameter($key)
    {
        return !is_null($this->getParameter($key));
    }

    /**
     * @return array
     */
    public function getTaskCountByState()
    {
        $taskStates = [
            'in-progress' => 'in_progress',
            'queued' => 'queued',
            'queued-for-assignment' => 'queued',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'awaiting-cancellation' => 'cancelled',
            'failed' => 'failed',
            'failed-no-retry-available' => 'failed',
            'failed-retry-available' => 'failed',
            'failed-retry-limit-reached' => 'failed',
            'skipped' => 'skipped'
        ];

        $taskCountByState = [];
        $taskCountByStateData = array_key_exists('task_count_by_state', $this->source)
            ? $this->source['task_count_by_state']
            : [];

        foreach ($taskStates as $taskState => $translatedState) {
            if (!isset($taskCountByState[$translatedState])) {
                $taskCountByState[$translatedState] = 0;
            }

            if (array_key_exists($taskState, $taskCountByStateData)) {
                $taskCountByState[$translatedState] += $taskCountByStateData[$taskState];
            }
        }

        return $taskCountByState;
    }

    /**
     * @return array|null
     */
    public function getCrawl()
    {
        return $this->getProperty('crawl');
    }

    /**
     * @return int|float
     */
    public function getCompletionPercent()
    {
        $crawlData = $this->getCrawl();

        if (Test::STATE_CRAWLING === $this->getState() && !empty($crawlData)) {
            $crawl = $this->getCrawl();
            $discoveredUrlCount = $crawl['discovered_url_count'];

            if (0 === $discoveredUrlCount) {
                return 0;
            }

            return round(($discoveredUrlCount / $crawl['limit']) * 100);
        }

        if (0 === $this->getTaskCount()) {
            return 0;
        }

        $finishedCount = 0;
        foreach ($this->getTaskCountByState() as $stateName => $taskCount) {
            if (in_array($stateName, $this->taskFinishedStates)) {
                $finishedCount += $taskCount;
            }
        }

        if ($finishedCount === $this->getTaskCount()) {
            return 100;
        }

        $requiredPrecision = floor(log10($this->getTaskCount())) - 1;

        if ($requiredPrecision == 0) {
            return floor(($finishedCount / $this->getTaskCount()) * 100);
        }

        return round(($finishedCount / $this->getTaskCount()) * 100, $requiredPrecision);
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return array_merge(
            $this->source,
            [
                'task_count_by_state' => $this->getTaskCountByState(),
                'completion_percent' => $this->getCompletionPercent(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->getProperty('user');
    }

    /**
     * @return bool
     */
    public function getIsPublic()
    {
        return $this->getProperty('is_public');
    }

    /**
     * @return int
     */
    public function getErroredTaskCount()
    {
        return $this->getProperty('errored_task_count');
    }

    /**
     * @return int
     */
    public function getCancelledTaskCount()
    {
        return $this->getProperty('cancelled_task_count');
    }

    /**
     * @return int
     */
    public function getSkippedTaskCount()
    {
        return $this->getProperty('skipped_task_count');
    }

    /**
     * @return int
     */
    public function getWarningedTaskCount()
    {
        return $this->getProperty('warninged_task_count');
    }

    /**
     * @return int
     */
    public function getErrorFreeTaskCount()
    {
        return $this->getTaskCount() - $this->getErroredTaskCount() - $this->getCancelledTaskCount();
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->getProperty('website');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getProperty('id');
    }

    /**
     * @return array
     */
    public function getAmmendments()
    {
        return $this->getProperty('ammendments');
    }

    /**
     * @return Rejection
     */
    public function getRejection()
    {
        if (!$this->hasProperty('rejection')) {
            return null;
        }

        return new Rejection($this->getProperty('rejection'));
    }

    /**
     * @return bool
     */
    public function hasRejection()
    {
        return !is_null($this->getRejection());
    }

    /**
     * @return bool
     */
    public function isFullSite()
    {
        return Test::TYPE_FULL_SITE === $this->getType();
    }

    /**
     * @return bool
     */
    public function isSingleUrl()
    {
        return Test::TYPE_SINGLE_URL === $this->getType();
    }

    /**
     * @return ArrayCollection
     */
    public function getOwners()
    {
        if (is_null($this->owners)) {
            $ownersData = $this->getProperty('owners');
            $ownersList = empty($ownersData)
                ? []
                : $ownersData;

            $this->owners = new ArrayCollection($ownersList);
        }

        return $this->owners;
    }
}
