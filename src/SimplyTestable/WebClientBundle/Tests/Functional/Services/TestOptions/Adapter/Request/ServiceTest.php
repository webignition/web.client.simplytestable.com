<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseTestCase;

abstract class ServiceTest extends BaseTestCase {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $requestData;


    /**
     * @var \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    private $taskTypeService = null;
    
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
        $this->getRequestAdapter()->setAvailableTaskTypes($this->getTaskTypeService()->getAvailable());
        $this->getRequestAdapter()->setAvailableFeatures($this->getAvailableFeatures());
        
        return $this->getRequestAdapter()->getTestOptions();
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter
     */
    protected function getRequestAdapter() {
        return $this->container->get('simplytestable.services.testoptions.adapter.request');
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
     * @return \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    protected function getTaskTypeService() {
        if (is_null($this->taskTypeService)) {
            $this->taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
            $this->taskTypeService->setUser($this->getUserService()->getPublicUser());

//            if (!$this->getUser()->equals($this->getUserService()->getPublicUser())) {
//                $this->taskTypeService->setUserIsAuthenticated();
//            }
        }

        return $this->taskTypeService;
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    private function getUserService() {
        return $this->container->get('simplytestable.services.userservice');
    }

}
