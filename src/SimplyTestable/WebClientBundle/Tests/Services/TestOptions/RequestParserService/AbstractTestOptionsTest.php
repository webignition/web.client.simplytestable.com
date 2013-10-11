<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

abstract class AbstractTestOptionsTest extends BaseTestCase {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $requestData;
    
    public function setUp() {
        parent::setUp();
        $this->requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
    }
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected function getRequestData() {
        return $this->requestData;
    }   
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestOptions
     */
    protected function getTestOptions() {        
        $this->getTestOptionsRequestParserService()->setRequestData($this->requestData);

        $testOptionsParameters = $this->container->getParameter('test_options');     

        $this->getTestOptionsRequestParserService()->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
        $this->getTestOptionsRequestParserService()->setAvailableTaskTypes($this->getAvailableTaskTypes());        
        
        return $this->getTestOptionsRequestParserService()->getTestOptions();        
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.adapter.request');
    }  
    
    
    /**
     * 
     * @return array
     */
    private function getAvailableTaskTypes() {
        $this->getAvailableTaskTypeService()->setUser($this->getUser());
        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());
        
        return $this->getAvailableTaskTypeService()->get();    
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }     
}
