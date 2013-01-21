<?php

namespace SimplyTestable\WebClientBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="TaskOutput",
 *     indexes={
 *         @ORM\Index(name="hash_idx", columns={"hash"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\TaskOutputRepository")
 * 
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
     *
     * @var string
     * @ORM\Column(type="string", nullable=true, length=32)
     */
    protected $hash;       
    

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
    
    
    /**
     * 
     * @return boolean
     */
    public function hasWarnings() {
        return $this->getWarningCount() > 0;
    }
    
    
    /**
     * Set hash
     *
     * @param string $hash
     * @return Task
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    } 
    
    
    /**
     * 
     * @return Task
     */
    public function generateHash() {        
        return $this->setHash(md5('content:'.$this->getContent().'
        type:'.$this->getType().'
        error-count:'.$this->getErrorCount().'
        warning-count:'.$this->getWarningCount()));
    }    
    
}