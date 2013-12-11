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
        $this->getTestOptionsRequestParserService()->setAvailableFeatures($this->getAvailableFeatures());
        
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
        return $this->getAvailableTaskTypeService()->get();    
    } 
    
    
    /**
     * 
     * @return array
     */
    private function getAvailableFeatures() {
        $testOptionsParameters = $this->container->getParameter('test_options');  
        return $testOptionsParameters['features'];        
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }        
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    private function getUserService() {
        return $this->container->get('simplytestable.services.userservice');
    }     
}
