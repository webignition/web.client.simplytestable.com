<?php

namespace AppBundle\Event\MailChimp;

use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

class Event extends BaseEvent {
    
    const TYPE_KEY = 'type';
    const DATA_KEY = 'data';
    const FIRED_AT_KEY = 'fired_at';
    const DATA_LIST_ID_KEY = 'list_id';
    
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
            throw new \UnexpectedValueException('Event raw data is missing "' . self::TYPE_KEY . '"', 1);
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
            throw new \UnexpectedValueException('Event raw data is missing "' . self::DATA_KEY . '"', 2);
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
            throw new \UnexpectedValueException('Event raw data is missing "' . self::FIRED_AT_KEY . '"', 3);
        }
        
        return new \DateTime($this->rawEventData->get(self::FIRED_AT_KEY));
    }    
    
    
    /**
     * 
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getListId() {
        if (!array_key_exists(self::DATA_LIST_ID_KEY, $this->getData())) {
            throw new \UnexpectedValueException('Event data is missing "' . self::DATA_LIST_ID_KEY . '"', 4);
        }
        
        return $this->getData()[self::DATA_LIST_ID_KEY];
    }
}