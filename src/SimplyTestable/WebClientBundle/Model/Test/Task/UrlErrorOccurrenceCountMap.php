<?php
namespace SimplyTestable\WebClientBundle\Model\Test\Task;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class UrlErrorOccurrenceCountMap  {

    /**
     * @var string
     */
    private $url = '';


    /**
     * @var int
     */
    private $occurrenceCount = 0;


    /**
     * @param string $url
     * @param int $occurrenceCount
     */
    public function __construct($url, $occurrenceCount) {
        $this->setUrl($url);
        $this->setOccurrenceCount($occurrenceCount);
    }


    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }


    /**
     * @param int $occurrenceCount
     * @return $this
     */
    public function setOccurrenceCount($occurrenceCount) {
        $this->occurrenceCount = $occurrenceCount;
        return $this;
    }
    
}