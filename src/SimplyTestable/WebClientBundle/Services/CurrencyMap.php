<?php

namespace SimplyTestable\WebClientBundle\Services;

class CurrencyMap
{
    /**
     * @var array
     */
    private $currencyMap;

    /**
     * @param array $currencyMap
     */
    public function __construct(array $currencyMap = [])
    {
        $this->currencyMap = $currencyMap;
    }

    /**
     * @return array
     */
    public function getCurrencyMap()
    {
        return $this->currencyMap;
    }
}
