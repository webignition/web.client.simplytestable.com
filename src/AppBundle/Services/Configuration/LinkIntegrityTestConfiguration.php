<?php

namespace AppBundle\Services\Configuration;

class LinkIntegrityTestConfiguration
{
    /**
     * @var string[]
     */
    private $excludedDomains;

    /**
     * @param string[] $excludedDomains
     */
    public function __construct(array $excludedDomains = [])
    {
        $this->excludedDomains = $excludedDomains;
    }

    /**
     * @return string[]
     */
    public function getExcludedDomains()
    {
        return $this->excludedDomains;
    }
}
