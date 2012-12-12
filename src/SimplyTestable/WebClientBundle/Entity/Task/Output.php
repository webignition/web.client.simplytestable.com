<?php

namespace SimplyTestable\WebClientBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="TaskOutput")
 * @SerializerAnnotation\ExclusionPolicy("all") 
 */
class Output {
    
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
     * @ORM\Column(type="text", nullable=true)
     * @SerializerAnnotation\Expose
     * @SerializerAnnotation\Accessor(getter="getPublicSerialisedContent")
     */
    private $content;
    
    /**
     *
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $type;
    
    
    /**
     *
     * @var SimplyTestable\WebClientBundle\Entity\Task\Task
     * 
     * @ORM\OneToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Task\Task", inversedBy="output")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false)     
     */
    protected $task;
    
    
    /**
     *
     * @var Result 
     */
    private $result;
    
    
    /**
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     * @SerializerAnnotation\Expose
     */    
    private $errorCount = 0;
    
    
    /**
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     * @SerializerAnnotation\Expose
     */
    private $warningCount = 0;
    

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
     * Set content
     *
     * @param string $content
     * @return TaskOutput
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
      

    /**
     * Set type
     *
     * @param string $type
     * @return Output
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
     * Set task
     *
     * @param SimplyTestable\WebClientBundle\Entity\Task\Task $task
     * @return Output
     */
    public function setTask(\SimplyTestable\WebClientBundle\Entity\Task\Task $task)
    {
        $this->task = $task;
    
        return $this;
    }

    /**
     * Get task
     *
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
    
    
    /**
     *
     * @return string
     */
    public function getPublicSerialisedContent() {
        return $this->getResult();
    }
    
    
    /**
     *
     * @param Result $result
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Output 
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
        return $this;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\Result
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * Set error count
     *
     * @param int $errorCount
     * @return Output
     */
    public function setErrorCount($errorCount)
    {
        $this->errorCount = $errorCount;
    
        return $this;
    }

    /**
     * Get error count
     *
     * @return int 
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }
    
    
    /**
     *
     * @return boolean
     */
    public function hasErrors() {
        return $this->getErrorCount() > 0;
    }
    
    
    /**
     * Set warningCount
     *
     * @param integer $warningCount
     * @return Output
     */
    public function setWarningCount($warningCount)
    {
        $this->warningCount = $warningCount;
    
        return $this;
    }

    /**
     * Get warningCount
     *
     * @return integer 
     */
    public function getWarningCount()
    {
        return $this->warningCount;
    }    
    
}