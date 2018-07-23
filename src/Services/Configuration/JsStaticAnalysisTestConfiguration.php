<?php

namespace App\Services\Configuration;

class JsStaticAnalysisTestConfiguration
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
