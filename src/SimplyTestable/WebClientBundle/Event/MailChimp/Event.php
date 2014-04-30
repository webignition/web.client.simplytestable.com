<?php

namespace SimplyTestable\WebClientBundle\Event\MailChimp;

use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

class Event extends BaseEvent {
    
    const TYPE_KEY = 'type';
    const DATA_KEY = 'data';
    const FIRED_AT_KEY = 'fired_at';
    
    /**
     *
     * @var ParameterBag
     */
    private $rawEventData;
    
    /**
     * 
     * @param ParameterBag $rawEventData
     */
    public function __construct(ParameterBag $rawEventData) {
        $this->rawEventData = $rawEventData;
    }
    
    
    /**
     * 
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getType() {
        if (!$this->rawEventData->has(self::TYPE_KEY)) {
            throw new \UnexpectedValueException('Event raw data missing is missing "' . self::TYPE_KEY . '"', 1);
        }
        
        return $this->rawEventData->get(self::TYPE_KEY);
    }
    
    
    /**
     * 
     * @return array
     * @throws \UnexpectedValueException
     */
    public function getData() {
        if (!$this->rawEventData->has(self::DATA_KEY)) {
            throw new \UnexpectedValueException('Event raw data missing is missing "' . self::DATA_KEY . '"', 2);
        }
        
        return $this->rawEventData->get(self::DATA_KEY);
    } 
    
    
    /**
     * 
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public function getFiredAt() {
        if (!$this->rawEventData->has(self::FIRED_AT_KEY)) {
            throw new \UnexpectedValueException('Event raw data missing is missing "' . self::FIRED_AT_KEY . '"', 3);
        }
        
        return new \DateTime($this->rawEventData->get(self::FIRED_AT_KEY));
    }    
    
    
}