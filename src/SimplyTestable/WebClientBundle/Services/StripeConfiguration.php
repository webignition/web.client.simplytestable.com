<?php

namespace SimplyTestable\WebClientBundle\Services;

class StripeConfiguration
{
    /**
     * @var string
     */
    private $publishableKey;

    /**
     * @param string $publishableKey
     */
    public function __construct($publishableKey)
    {
        $this->publishableKey = $publishableKey;
    }

    /**
     * @return string
     */
    public function getPublishableKey()
    {
        return $this->publishableKey;
    }
}
