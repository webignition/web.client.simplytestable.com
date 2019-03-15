<?php

namespace App\Model;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

class Test
{
    private $entity;
    private $website;
    private $user;
    private $state;
    private $type;
    private $taskTypes;
    private $urlCount;
    private $errorCount;
    private $warningCount;
    private $remoteTaskCount;
    private $tasksWithErrorsCount;
    private $cancelledTaskCount;
    private $parameters;
    private $amendments;

    public function __construct(
        TestEntity $entity,
        string $website,
        string $user,
        string $state,
        string $type,
        array $taskTypes,
        int $urlCount,
        int $errorCount,
        int $warningCount,
        int $remoteTaskCount,
        int $tasksWithErrorsCount,
        int $cancelledTaskCount,
        string $encodedParameters,
        array $amendments
    ) {
        $this->entity = $entity;
        $this->website = $website;
        $this->user = $user;
        $this->state = $state;
        $this->type = $type;
        $this->taskTypes = $taskTypes;
        $this->urlCount = $urlCount;
        $this->errorCount = $errorCount;
        $this->warningCount = $warningCount;
        $this->remoteTaskCount = $remoteTaskCount;
        $this->tasksWithErrorsCount = $tasksWithErrorsCount;
        $this->cancelledTaskCount = $cancelledTaskCount;

        $decodedParameters = $parameters = json_decode($encodedParameters, true);
        $this->parameters = is_array($decodedParameters) ? $decodedParameters : [];

        $this->amendments = $amendments;
    }

    public function getTestId(): int
    {
        return $this->entity->getTestId();
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
        return $this->errorCount;
    }

    public function getWarningCount(): int
    {
        return $this->warningCount;
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
}
