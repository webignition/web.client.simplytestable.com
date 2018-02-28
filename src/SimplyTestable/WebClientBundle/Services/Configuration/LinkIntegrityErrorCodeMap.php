<?php

namespace SimplyTestable\WebClientBundle\Services\Configuration;

class LinkIntegrityErrorCodeMap
{
    /**
     * @var array
     */
    private $errorCodeMap;

    /**
     * @param array $errorCodeMap
     */
    public function __construct(array $errorCodeMap = [])
    {
        $this->errorCodeMap = $errorCodeMap;
    }

    /**
     * @return array
     */
    public function getErrorCodeMap()
    {
        return $this->errorCodeMap;
    }
}
