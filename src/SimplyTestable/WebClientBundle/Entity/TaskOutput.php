<?php

namespace SimplyTestable\WebClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="TaskOutput",
 *       uniqueConstraints={
 *           @ORM\UniqueConstraint(name="remote_id_idx", columns={"remoteId"})
 *       }
 * )
 */
class TaskOutput {
    
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
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $remoteId;
    
    
    /**
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    

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
     * Set remoteId
     *
     * @param integer $remoteId
     * @return TaskOutput
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;
    
        return $this;
    }

    /**
     * Get remoteId
     *
     * @return integer 
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }
}