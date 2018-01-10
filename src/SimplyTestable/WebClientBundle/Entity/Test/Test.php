<?php

namespace SimplyTestable\WebClientBundle\Entity\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
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
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\TestRepository")
 * @SerializerAnnotation\ExclusionPolicy("all")
 */
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
    const STATE_CRAWLING = 'crawling';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SerializerAnnotation\Expose
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $testId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $user;

    /**
     * @var NormalisedUrl
     *
     * @ORM\Column(type="text", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $state;

    /**
     * @var DoctrineCollection
     *
     * @ORM\OneToMany(targetEntity="SimplyTestable\WebClientBundle\Entity\Task\Task", mappedBy="test", cascade={"persist"})
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
     * @SerializerAnnotation\Expose
     */
    private $taskTypes;

    /**
     * @var TimePeriod
     *
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\TimePeriod", cascade={"persist"})
     * @SerializerAnnotation\Expose
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
    private $urlCount;

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

    /**
     * @return int
     */
    public function getCompletionPercent()
    {
        if ($this->getState() == 'new') {
            return 0;
        }

        if ($this->getState() == 'completed') {
            return 100;
        }

        if ($this->getTaskCount() == 0) {
            return 100;
        }

        return (floor($this->getFinishedTaskCount() / $this->getTaskCount() * 100));
    }

    /**
     * @return int
     */
    private function getFinishedTaskCount()
    {
        $finishedTaskStates = array(
            'completed',
            'failed',
            'failed-no-retry-available',
            'failed-retry-available',
            'failed-retry-limit-reached'
        );

        $finishedTaskCount = 0;

        foreach ($finishedTaskStates as $finishedTaskState) {
            $finishedTaskCount += $this->getTaskCountByState($finishedTaskState);
        }

        return $finishedTaskCount;
    }

    /**
     * @return int
     */
    public function getTaskCount()
    {
        return count($this->tasks);
    }

    /**
     * @param string $state
     *
     * @return int
     */
    public function getTaskCountByState($state)
    {
        if ($this->getTaskCount() == 0) {
            return 0;
        }

        $total = 0;
        foreach ($this->getTasks() as $task) {
            if ($task->getState() == $state) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * @param int $urlCount
     *
     * @return Test
     */
    public function setUrlCount($urlCount)
    {
        $this->urlCount = $urlCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getUrlCount()
    {
        return $this->urlCount;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $user
     *
     * @return Test
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param NormalisedUrl $website
     *
     * @return Test
     */
    public function setWebsite(NormalisedUrl $website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return NormalisedUrl
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $state
     *
     * @return Test
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param array $taskTypes
     *
     * @return Test
     */
    public function setTaskTypes($taskTypes)
    {
        $this->taskTypes = $taskTypes;

        return $this;
    }

    /**
     * @return DoctrineCollection|string[]
     */
    public function getTaskTypes()
    {
        return $this->taskTypes;
    }

    /**
     * @param Task $task
     *
     * @return Test
     */
    public function addTask(Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * @return DoctrineCollection|Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param TimePeriod $timePeriod
     *
     * @return Test
     */
    public function setTimePeriod(TimePeriod $timePeriod = null)
    {
        $this->timePeriod = $timePeriod;

        return $this;
    }

    /**
     * @return TimePeriod
     */
    public function getTimePeriod()
    {
        return $this->timePeriod;
    }

    /**
     * @param integer $testId
     *
     * @return Test
     */
    public function setTestId($testId)
    {
        $this->testId = $testId;

        return $this;
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
            /* @var $comparatorTask Task */
            if ($comparatorTask->getUrl() == $task->getUrl() && $comparatorTask->getType() == $task->getType() && $comparatorTask->getTaskId() == $task->getTaskId()) {
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
}
