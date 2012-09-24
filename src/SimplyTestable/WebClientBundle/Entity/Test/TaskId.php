<?php

namespace SimplyTestable\WebClientBundle\Entity\Test;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(
 *     name="TestTaskId"
 * )
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\TaskRepository")
 * 
 * @SerializerAnnotation\ExclusionPolicy("all")
 */
class TaskId {
    
    /**
     * 
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SerializerAnnotation\Expose
     */
    private $id;    
    
    /**
     * 
     * @var int 
     * 
     * @ORM\Column(type="integer", nullable=false)
     * @SerializerAnnotation\Expose
     */    
    private $taskId;
    
    /**
     *
     * @var SimplyTestable\WebClientBundle\Entity\Test\Test
     * 
     * @ORM\ManyToOne(targetEntity="SimplyTestable\WebClientBundle\Entity\Test\Test", inversedBy="tasks")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=false)     
     */
    private $test;
    

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

    /**
     * Set taskId
     *
     * @param integer $taskId
     * @return Task
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    
        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer 
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
}