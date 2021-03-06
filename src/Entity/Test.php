<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Task\Task;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="Test",
 *     indexes={
 *         @ORM\Index(name="testId_idx", columns={"testId"})
 *     }
 * )
 */
class Test
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $testId;

    /**
     * @var DoctrineCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Task\Task", mappedBy="test", cascade={"persist"})
     */
    private $tasks;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $taskIdCollection;

    /**
     * @var array
     */
    private $taskIds = null;

    private function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->taskIds = [];
        $this->taskIdCollection = '';
    }

    public static function create(int $testId): Test
    {
        $test = new Test();
        $test->testId = $testId;

        return $test;
    }

    public function addTask(Task $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * @return DoctrineCollection|Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    public function getTestId(): int
    {
        return $this->testId;
    }

    /**
     * @return int[]
     */
    public function getTaskIds(): array
    {
        if (is_null($this->taskIds)) {
            $this->taskIds = [];

            if (!empty($this->taskIdCollection)) {
                $this->taskIds = [];
                $rawTaskIds = explode(',', $this->taskIdCollection);

                foreach ($rawTaskIds as $rawTaskId) {
                    $this->taskIds[] = (int)$rawTaskId;
                }
            }
        }

        return $this->taskIds;
    }

    public function setTaskIdCollection(string $taskIdCollection)
    {
        $this->taskIdCollection = $taskIdCollection;
        $this->taskIds = null;
    }

    public function getErrorCount(): int
    {
        $errorCount = 0;

        foreach ($this->tasks as $task) {
            /* @var $task Task */
            if ($task->hasOutput()) {
                $errorCount += $task->getOutput()->getErrorCount();
            }
        }

        return $errorCount;
    }

    public function getWarningCount(): int
    {
        $warningCount = 0;

        foreach ($this->tasks as $task) {
            /* @var $task Task */
            if ($task->hasOutput()) {
                $warningCount += $task->getOutput()->getWarningCount();
            }
        }

        return $warningCount;
    }
}
