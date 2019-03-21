<?php

namespace App\Model;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

class Test
{
    const STATE_STARTING = 'new';
    const STATE_CANCELLED = 'cancelled';
    const STATE_COMPLETED = 'completed';
    const STATE_IN_PROGRESS = 'in-progress';
    const STATE_PREPARING = 'preparing';
    const STATE_QUEUED = 'queued';
    const STATE_FAILED_NO_SITEMAP = 'failed-no-sitemap';
    const STATE_REJECTED = 'rejected';
    const STATE_RESOLVING = 'resolving';
    const STATE_RESOLVED = 'resolved';

    /**
     * @var string[]
     */
    private $finishedStates = array(
        self::STATE_REJECTED,
        self::STATE_CANCELLED,
        self::STATE_COMPLETED,
        self::STATE_FAILED_NO_SITEMAP,
    );

    private $entity;
    private $website;
    private $user;
    private $state;
    private $type;
    private $taskTypes;
    private $urlCount;
    private $remoteTaskCount;
    private $tasksWithErrorsCount;
    private $cancelledTaskCount;
    private $parameters;
    private $amendments;
    private $completionPercent;
    private $taskCountByState;
    private $rejection;
    private $crawlData;
    private $isPublic;
    private $taskOptions;
    private $owners;

    public function __construct(
        TestEntity $entity,
        string $website,
        string $user,
        string $state,
        string $type,
        array $taskTypes,
        int $urlCount,
        int $remoteTaskCount,
        int $tasksWithErrorsCount,
        int $cancelledTaskCount,
        string $encodedParameters,
        array $amendments,
        int $completionPercent,
        array $taskCountByState,
        array $crawlData,
        array $rejection,
        bool $isPublic,
        array $taskTypeOptions,
        array $owners
    ) {
        $this->entity = $entity;
        $this->website = $website;
        $this->user = $user;
        $this->state = $state;
        $this->type = $type;
        $this->taskTypes = $taskTypes;
        $this->urlCount = $urlCount;
        $this->remoteTaskCount = $remoteTaskCount;
        $this->tasksWithErrorsCount = $tasksWithErrorsCount;
        $this->cancelledTaskCount = $cancelledTaskCount;

        $decodedParameters = $parameters = json_decode($encodedParameters, true);
        $this->parameters = is_array($decodedParameters) ? $decodedParameters : [];

        $this->amendments = $amendments;
        $this->completionPercent = $completionPercent;
        $this->taskCountByState = $taskCountByState;
        $this->crawlData = $crawlData;
        $this->rejection = $rejection;
        $this->isPublic = $isPublic;
        $this->taskOptions = $taskTypeOptions;
        $this->owners = $owners;
    }

    public function getEntity(): TestEntity
    {
        return $this->entity;
    }

    public function getTestId(): int
    {
        return $this->entity->getTestId();
    }

    /**
     * @return int[]
     */
    public function getTaskIds(): array
    {
        return $this->entity->getTaskIds();
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTaskTypes(): array
    {
        return $this->taskTypes;
    }

    public function getUrlCount(): int
    {
        return $this->urlCount;
    }

    public function getErrorCount(): int
    {
        return $this->entity->getErrorCount();
    }

    public function getWarningCount(): int
    {
        return $this->entity->getWarningCount();
    }

    /**
     * @return DoctrineCollection|Task[]
     */
    public function getTasks(): DoctrineCollection
    {
        $tasks = $this->entity->getTasks();

        if (!$tasks instanceof DoctrineCollection) {
            $tasks = new ArrayCollection();
        }

        return $tasks;
    }

    public function getLocalTaskCount(): int
    {
        return count($this->getTasks());
    }

    public function getRemoteTaskCount(): int
    {
        return $this->remoteTaskCount;
    }

    public function getTasksWithErrorsCount(): int
    {
        return $this->tasksWithErrorsCount;
    }

    public function getCancelledTaskCount(): int
    {
        return $this->cancelledTaskCount;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key)
    {
        return $this->parameters[$key] ?? null;
    }

    public function getAmendments(): array
    {
        return $this->amendments;
    }

    public function getCompletionPercent(): int
    {
        return $this->completionPercent;
    }

    public function getTaskCountByState(): array
    {
        return $this->taskCountByState;
    }

    public function getRejection(): array
    {
        return $this->rejection;
    }

    public function requiresRemoteTasks(): bool
    {
        return $this->getRemoteTaskCount() > $this->getLocalTaskCount();
    }

    public function getCrawlData(): array
    {
        return $this->crawlData;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function getTaskOptions(): array
    {
        return $this->taskOptions;
    }

    public function getOwners(): array
    {
        return $this->owners;
    }

    public function isFinished(): bool
    {
        return in_array($this->state, $this->finishedStates);
    }

    public function getHash(): string
    {
        return md5(json_encode([
            'test_id' => $this->getTestId(),
            'state' => $this->getState(),
            'requires_remote_tasks' => $this->requiresRemoteTasks(),
        ]));
    }
}
