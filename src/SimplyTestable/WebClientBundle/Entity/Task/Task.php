<?php

namespace SimplyTestable\WebClientBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;

use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Output as TaskOutput;
use webignition\NormalisedUrl\NormalisedUrl;


/**
 * 
 * @ORM\Entity
 */
class Task {
    
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
     * 
     * @var string 
     * 
     * @ORM\Column(type="text", nullable=false)
     */
    private $url;
    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;
    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    private $worker;
    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;
    
    
    /**
     *
     * @var SimplyTestable\WebClientBundle\Entity\TimePeriod
     * 
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\TimePeriod", cascade={"persist"})
     */
    private $timePeriod;
    
    
    /**
     *
     * @var TaskOutput
     * 
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Task\Output", cascade={"persist"})
     */
    private $output;
    
    
    /**
     *
     * @var SimplyTestable\WebClientBundle\Entity\Test\Test
     * 
     * @ORM\ManyToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Test\Test", inversedBy="tasks")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=false)     
     */
    protected $test;
    
    
    public function __construct() {
        $this->timePeriod = new TimePeriod();
    }
    
    
    /**
     *
     * @param Task $task
     * @return boolean 
     */
    public function equals(Task $task) {
        if ((string)$this->getUrl() != (string)$task->getUrl()) {
            return false;
        }
        
        if ($this->getType() != $task->getType()) {
            return false;
        }
        
        return true;
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
     * Set url
     *
     * @param string $url
     * @return Task
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Task
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
     * Set worker
     *
     * @param string $worker
     * @return Task
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    
        return $this;
    }

    /**
     * Get worker
     *
     * @return string 
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Task
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set timePeriod
     *
     * @param SimplyTestable\WebClientBundle\Entity\TimePeriod $timePeriod
     * @return Task
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
     * Set output
     *
     * @param SimplyTestable\WebClientBundle\Entity\Task\Output $output
     * @return Task
     */
    public function setOutput(\SimplyTestable\WebClientBundle\Entity\Task\Output $output = null)
    {
        $this->output = $output;
    
        return $this;
    }

    /**
     * Get output
     *
     * @return SimplyTestable\WebClientBundle\Entity\Task\Output 
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set test
     *
     * @param SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return Task
     */
    public function setTest(\SimplyTestable\WebClientBundle\Entity\Test\Test $test)
    {
        $this->test = $test;
    
        return $this;
    }

    /**
     * Get test
     *
     * @return SimplyTestable\WebClientBundle\Entity\Test\Test 
     */
    public function getTest()
    {
        return $this->test;
    }
}