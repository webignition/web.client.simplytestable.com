<?php

namespace SimplyTestable\WebClientBundle\Model;

class TimePeriod
{    
    /**
     *
     * @var \DateTime
     */
    protected $startDateTime;
    
    
    /**
     *
     * @var \DateTime
     */
    protected $endDateTime;


    /**
     * Set startDateTime
     *
     * @param \DateTime $startDateTime
     * @return TimePeriod
     */
    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;
    
        return $this;
    }

    /**
     * Get startDateTime
     *
     * @return \DateTime 
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Set endDateTime
     *
     * @param \DateTime $endDateTime
     * @return TimePeriod
     */
    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;
    
        return $this;
    }

    /**
     * Get endDateTime
     *
     * @return \DateTime 
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }
}