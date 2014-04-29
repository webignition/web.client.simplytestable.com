<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;

abstract class ViewController extends BaseViewController implements IEFiltered, Cacheable {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request) {
        $this->request = $request;
    }   
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest() {
        return (is_null($this->request)) ? $this->get('request') : $this->request;
    }

}