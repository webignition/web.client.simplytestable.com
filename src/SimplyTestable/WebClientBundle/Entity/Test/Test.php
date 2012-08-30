<?php

namespace SimplyTestable\WebClientBundle\Entity\Test;

use Doctrine\ORM\Mapping as ORM;

use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * 
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
class Test {
    
    
    /**
     * 
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    
    /**
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=false)
     */
    private $testId;
    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", nullable=false)
     */
    private $user;    
    
    
    /**
     *
     * @var NormalisedUrl 
     * 
     * @ORM\Column(type="string", nullable=false)
     */
    private $website;
    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;
    
    
    /**
     *
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="SimplyTestable\WebClientBundle\Entity\Task\Task", mappedBy="test", cascade={"persist"})
     */
    private $tasks;
    
    
    /**
     *
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\Column(type="array", nullable=false)
     */
    private $taskTypes;    
    
    
    /**
     *
     * @var SimplyTestable\WebClientBundle\Entity\TimePeriod
     * 
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\TimePeriod", cascade={"persist"})
     */
    private $timePeriod;
    
    
    /**
     *
     * @var int
     */
    private $urlCount;
    
    
    /**
     *
     * @return int
     */
    public function getCompletionPercent() {       
        if ($this->getState() == 'new') {
            return 0;
        }
        
        if ($this->getState() == 'completed') {
            return 100;
        }
        
        if ($this->getTaskCount() == 0) {
            return 100;
        }        
        
        return (floor($this->getTaskCountByState('completed') / $this->getTaskCount() * 100));        
    }
    
    
    /**
     *
     * @return int 
     */
    public function getTaskCount() {
        return count($this->tasks);
    }
    
    
    /**
     *
     * @param string $state
     * @return int 
     */
    public function getTaskCountByState($state) {
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
     *
     * @param int $urlCount
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test 
     */
    public function setUrlCount($urlCount) {
        $this->urlCount = $urlCount;
        return $this;
    }
    
    
    /**
     *
     * @return int
     */
    public function getUrlCount() {
        return $this->urlCount;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->timePeriod = new TimePeriod();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Test
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set website
     *
     * @param NormalisedUrl $website
     * @return Test
     */
    public function setWebsite(NormalisedUrl $website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return NormalisedUrl 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Test
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set taskTypes
     *
     * @param array $taskTypes
     * @return Test
     */
    public function setTaskTypes($taskTypes)
    {
        $this->taskTypes = $taskTypes;
    
        return $this;
    }

    /**
     * Get taskTypes
     *
     * @return array 
     */
    public function getTaskTypes()
    {
        return $this->taskTypes;
    }

    /**
     * Add tasks
     *
     * @param SimplyTestable\WebClientBundle\Entity\Task\Task $tasks
     * @return Test
     */
    public function addTask(\SimplyTestable\WebClientBundle\Entity\Task\Task $task)
    {
        $this->tasks[] = $task;
    
        return $this;
    }

    /**
     * Remove tasks
     *
     * @param SimplyTestable\WebClientBundle\Entity\Task\Task $tasks
     */
    public function removeTask(\SimplyTestable\WebClientBundle\Entity\Task\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set timePeriod
     *
     * @param SimplyTestable\WebClientBundle\Entity\TimePeriod $timePeriod
     * @return Test
     */
    public function setTimePeriod(\SimplyTestable\WebClientBundle\Entity\TimePeriod $timePeriod = null)
    {
        $this->timePeriod = $timePeriod;
    
        return $this;
    }

    /**
     * Get timePeriod
     *
     * @return SimplyTestable\WebClientBundle\Entity\TimePeriod 
     */
    public function getTimePeriod()
    {
        return $this->timePeriod;
    }

    /**
     * Set testId
     *
     * @param integer $testId
     * @return Test
     */
    public function setTestId($testId)
    {
        $this->testId = $testId;
    
        return $this;
    }

    /**
     * Get testId
     *
     * @return integer 
     */
    public function getTestId()
    {
        return $this->testId;
    }
    
    
    /**
     *
     * @param Task $task
     * @return boolean 
     */
    public function hasTask(Task $task) {
        return $this->getTask($task) !== false;
    }
    
    
    /**
     *
     * @param Task $task
     * @return Task|false 
     */
    public function getTask(Task $task) {
        foreach ($this->getTasks() as $comparatorTask) {
            /* @var $comparatorTask Task */
            if ($comparatorTask->getUrl() == $task->getUrl() && $comparatorTask->getType() == $task->getType() && $comparatorTask->getTaskId() == $task->getTaskId()) {
                return $comparatorTask;
            }
        }
        
        return false;        
    }
}