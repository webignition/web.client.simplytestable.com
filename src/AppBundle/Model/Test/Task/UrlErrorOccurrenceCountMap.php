<?php

namespace AppBundle\Model\Test\Task;

class UrlErrorOccurrenceCountMap
{
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
    public function __construct($url, $occurrenceCount)
    {
        $this->setUrl($url);
        $this->setOccurrenceCount($occurrenceCount);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param int $occurrenceCount
     */
    public function setOccurrenceCount($occurrenceCount)
    {
        $this->occurrenceCount = $occurrenceCount;
    }
}
