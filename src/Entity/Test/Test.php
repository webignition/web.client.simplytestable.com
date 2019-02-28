<?php

namespace App\Entity\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TimePeriod;
use App\Entity\Task\Task;
use webignition\NormalisedUrl\NormalisedUrl;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="Test",
 *     indexes={
 *         @ORM\Index(name="user_idx", columns={"user"}),
 *         @ORM\Index(name="website_idx", columns={"website"}),
 *         @ORM\Index(name="state_idx", columns={"state"}),
 *         @ORM\Index(name="testId_idx", columns={"testId"})
 *     }
 * )
 */
class Test implements \JsonSerializable
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
    const STATE_CRAWLING = 'crawling';

    const TYPE_FULL_SITE = 'Full site';
    const TYPE_SINGLE_URL = 'Single URL';

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
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $user;

    /**
     * @var NormalisedUrl
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;

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

    /**
     * @var DoctrineCollection
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $taskTypes;

    /**
     * @var TimePeriod
     *
     * @ORM\OneToOne(targetEntity="App\Entity\TimePeriod", cascade={"persist"})
     */
    private $timePeriod;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var int
     */
    private $urlCount = null;

    /**
     * @var array
     */
    private $taskIdIndex;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->taskIds = new ArrayCollection();
        $this->timePeriod = new TimePeriod();
    }

    public function getTaskCount(): int
    {
        return count($this->tasks);
    }

    public function setUrlCount(? int $urlCount)
    {
        $this->urlCount = $urlCount;
    }

    public function getUrlCount(): ?int
    {
        return $this->urlCount;
    }

    public function setUser(string $user)
    {
        $this->user = $user;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setWebsite(NormalisedUrl $website)
    {
        $this->website = $website;
    }

    public function getWebsite(): ?NormalisedUrl
    {
        return $this->website;
    }

    public function setState(string $state)
    {
        $this->state = $state;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setTaskTypes(array $taskTypes)
    {
        $this->taskTypes = $taskTypes;
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

    public function setTimePeriod(TimePeriod $timePeriod)
    {
        $this->timePeriod = $timePeriod;
    }

    public function setTestId(int $testId)
    {
        $this->testId = $testId;
    }

    /**
     * @return int
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function hasTask(Task $task)
    {
        return array_key_exists($task->getTaskId(), $this->getTaskIdIndex());
    }

    /**
     * @param Task $task
     *
     * @return Task|false
     */
    public function getTask(Task $task)
    {
        foreach ($this->getTasks() as $comparatorTask) {
            $urlsAreEqual = $comparatorTask->getUrl() == $task->getUrl();
            $typesAreEqual = $comparatorTask->getType() == $task->getType();
            $taskIdsAreEqual = $comparatorTask->getTaskId() == $task->getTaskId();

            /* @var $comparatorTask Task */
            if ($urlsAreEqual && $typesAreEqual && $taskIdsAreEqual) {
                return $comparatorTask;
            }
        }

        return false;
    }

    /**
     * @return int[]
     */
    private function getTaskIdIndex()
    {
        if (is_null($this->taskIdIndex)) {
            $this->taskIdIndex = [];
            foreach ($this->getTasks() as $task) {
                $this->taskIdIndex[$task->getTaskId()] = true;
            }
        }

        return $this->taskIdIndex;
    }

    public function clearTasks()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return int[]
     */
    public function getTaskIds()
    {
        if (is_null($this->taskIds)) {
            if (is_null($this->getTaskIdCollection()) || $this->getTaskIdCollection() == '') {
                $this->taskIds = [];
            } else {
                $this->taskIds = [];
                $rawTaskIds = explode(',', $this->getTaskIdCollection());

                foreach ($rawTaskIds as $rawTaskId) {
                    $this->taskIds[] = (int)$rawTaskId;
                }
            }
        }

        return $this->taskIds;
    }

    /**
     * @return bool
     */
    public function hasTaskIds()
    {
        return count($this->getTaskIds()) > 0;
    }

    /**
     * @param string $taskIdCollection
     *
     * @return Test
     */
    public function setTaskIdColletion($taskIdCollection)
    {
        $this->taskIdCollection = $taskIdCollection;
        $this->taskIds = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getTaskIdCollection()
    {
        return $this->taskIdCollection;
    }

    /**
     * @param string $type
     *
     * @return Test
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        $errorCount = 0;

        foreach ($this->getTasks() as $task) {
            /* @var $task Task */
            if ($task->hasOutput()) {
                $errorCount += $task->getOutput()->getErrorCount();
            }
        }

        return $errorCount;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->getErrorCount() > 0;
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return $this->getWarningCount() > 0;
    }

    /**
     * @return int
     */
    public function getWarningCount()
    {
        $warningCount = 0;

        foreach ($this->getTasks() as $task) {
            /* @var $task Task */
            if ($task->hasOutput()) {
                $warningCount += $task->getOutput()->getWarningCount();
            }
        }

        return $warningCount;
    }

    /**
     * @param string $type
     *
     * @return int
     */
    public function getErrorCountByTaskType($type = '')
    {
        $count = 0;

        foreach ($this->getTasks() as $task) {
            /* @var $task Task */
            if ($task->hasOutput() && $task->getType() == $type) {
                $count += $task->getOutput()->getErrorCount();
            }
        }

        return $count;
    }

    /**
     * @param string $type
     *
     * @return int
     */
    public function getWarningCountByTaskType($type = '')
    {
        $count = 0;

        foreach ($this->getTasks() as $task) {
            /* @var $task Task */
            if ($task->hasOutput() && $task->getType() == $type) {
                $count += $task->getOutput()->getWarningCount();
            }
        }

        return $count;
    }

    /**
     * @return string
     */
    public function getFormattedWebsite()
    {
        return rawurldecode($this->getWebsite());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'test_id' => $this->testId,
            'user' => $this->user,
            'website' => (string)$this->website,
            'state' => $this->state,
            'taskTypes' => $this->taskTypes,
            'timePeriod' => $this->timePeriod->jsonSerialize(),
        ];
    }
}
