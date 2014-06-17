<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

abstract class ServiceTest extends BaseTestCase {
    
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
        $this->getRequestAdapter()->setRequestData($this->requestData);

        $testOptionsParameters = $this->container->getParameter('test_options');     

        $this->getRequestAdapter()->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
        $this->getRequestAdapter()->setAvailableTaskTypes($this->getAvailableTaskTypes());
        $this->getRequestAdapter()->setAvailableFeatures($this->getAvailableFeatures());
        
        return $this->getRequestAdapter()->getTestOptions();
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    protected function getRequestAdapter() {
        return $this->container->get('simplytestable.services.testoptions.adapter.request');
    }  
    
    
    /**
     * 
     * @return array
     */
    protected function getAvailableTaskTypes() {
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
    protected function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }

}
