<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

interface Cacheable {    
    
    public function getCacheValidatorParameters();
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request);
    public function getRequest();
    
}