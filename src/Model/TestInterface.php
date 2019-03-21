<?php

namespace App\Model;

use App\Entity\Task\Task;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

interface TestInterface
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

    public function getTestId(): int;

    /**
     * @return int[]
     */
    public function getTaskIds(): array;
    public function getWebsite(): string;
    public function getUser(): string;
    public function getState(): string;
    public function getType(): string;

    /**
     * @return string[]
     */
    public function getTaskTypes(): array;
    public function getUrlCount(): int;
    public function getErrorCount(): int;
    public function getWarningCount(): int;
    /**
     * @return DoctrineCollection|Task[]
     */
    public function getTasks(): DoctrineCollection;
    public function getLocalTaskCount(): int;
    public function getRemoteTaskCount(): int;
    public function getTasksWithErrorsCount(): int;
    public function getCancelledTaskCount(): int;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key);
    public function getAmendments(): array;
    public function getCompletionPercent(): int;
    public function getTaskCountByState(): array;
    public function getRejection(): array;
    public function requiresRemoteTasks(): bool;
    public function getCrawlData(): array;
    public function isPublic(): bool;
    public function getTaskOptions(): array;

    /**
     * @return string[]
     */
    public function getOwners(): array;
    public function isFinished(): bool;
    public function getHash(): string;
}
