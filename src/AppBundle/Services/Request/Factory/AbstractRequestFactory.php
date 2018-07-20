<?php

namespace AppBundle\Services\Request\Factory;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractRequestFactory
{
    /**
     * @var ParameterBag
     */
    private $requestParameters;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestParameters = $requestStack->getCurrentRequest()->request;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getStringValueFromRequestParameters($key)
    {
        return trim($this->requestParameters->get($key));
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function getBooleanValueFromRequestParameters($key)
    {
        return (bool)$this->requestParameters->get($key);
    }
}
