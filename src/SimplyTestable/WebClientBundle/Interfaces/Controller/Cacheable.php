<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\Request;

interface Cacheable
{
    /**
     * @return array
     */
    public function getCacheValidatorParameters();

    /**
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * @return Request
     */
    public function getRequest();
}
