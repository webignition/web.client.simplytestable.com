<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;
use Symfony\Component\HttpFoundation\Request;

abstract class CacheableViewController extends ViewController implements Cacheable
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return (is_null($this->request)) ? $this->get('request') : $this->request;
    }
}
