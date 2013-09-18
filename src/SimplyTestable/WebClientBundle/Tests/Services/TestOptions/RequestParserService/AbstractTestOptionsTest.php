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
        $availableTaskTypes = $this->container->getParameter('available_task_types');       

        $this->getTestOptionsRequestParserService()->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
        $this->getTestOptionsRequestParserService()->setAvailableTaskTypes($availableTaskTypes['default']);        
        
        return $this->getTestOptionsRequestParserService()->getTestOptions();        
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    }  
}
