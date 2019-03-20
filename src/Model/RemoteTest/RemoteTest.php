<?php

namespace App\Model\RemoteTest;

use App\Entity\Test;
use App\Model\AbstractArrayBasedModel;
use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\Common\Collections\ArrayCollection;

class RemoteTest extends AbstractArrayBasedModel
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

    public function getState(): string
    {
        $state = trim($this->getProperty('state'));
        $crawlData = $this->getCrawl();

        if (Test::STATE_FAILED_NO_SITEMAP === $state && !empty($crawlData)) {
            return Test::STATE_CRAWLING;
        }

        return $state;
    }

    public function getType(): string
    {
        return trim($this->getProperty('type'));
    }

    public function getUrlCount(): int
    {
        return (int) $this->getProperty('url_count');
    }

    public function getTaskCount(): int
    {
        return (int) $this->getProperty('task_count');
    }

    public function getTaskTypes(): array
    {
        $sourceTaskTypes = $this->source['task_types'] ?? [];

        $taskTypes = [];

        foreach ($sourceTaskTypes as $taskTypeData) {
            $taskTypes[] = $taskTypeData['name'];
        }

        return $taskTypes;
    }

    public function getTaskTypeOptions(): array
    {
        return $this->getProperty('task_type_options') ?? [];
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

    public function getEncodedParameters(): string
    {
        return trim($this->getProperty('parameters'));
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasParameter($key)
    {
        return !is_null($this->getParameter($key));
    }

    public function getRawTaskCountByState(): array
    {
        return $this->getProperty('task_count_by_state') ?? [];
    }

    public function getTaskCountByState(): array
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

    public function getCrawl(): array
    {
        return $this->getProperty('crawl') ?? [];
    }

    public function getCompletionPercent(): int
    {
        $crawlData = $this->getCrawl();

        if (Test::STATE_CRAWLING === $this->getState() && !empty($crawlData)) {
            $crawl = $this->getCrawl();
            $discoveredUrlCount = $crawl['discovered_url_count'];

            if (0 === $discoveredUrlCount) {
                return 0;
            }

            return (int) round(($discoveredUrlCount / $crawl['limit']) * 100);
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
            return (int) floor(($finishedCount / $this->getTaskCount()) * 100);
        }

        return (int) round(($finishedCount / $this->getTaskCount()) * 100, $requiredPrecision);
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

    public function getUser()
    {
        return trim($this->getProperty('user'));
    }

    /**
     * @return bool
     */
    public function getIsPublic()
    {
        return $this->getProperty('is_public');
    }

    public function getErroredTaskCount(): int
    {
        return (int) $this->getProperty('errored_task_count');
    }

    public function getCancelledTaskCount(): int
    {
        return (int) $this->getProperty('cancelled_task_count');
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

    public function getWebsite(): string
    {
        return trim($this->getProperty('website'));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->getProperty('id');
    }

    public function getAmmendments(): array
    {
        $amendments = $this->getProperty('ammendments');

        return is_array($amendments) ? $amendments : [];
    }

    public function getRejection(): ?Rejection
    {
        $rejectionData = $this->getProperty('rejection');

        if (null === $rejectionData) {
            return null;
        }

        return new Rejection($rejectionData);
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
