<?php

namespace SimplyTestable\WebClientBundle\Tests;
use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\BrowserKit\Cookie;

abstract class BaseSimplyTestableTestCase extends BaseTestCase {
    
    const APP_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\AppController';    
    const TEST_PROGRESS_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestProgressController';    
    const TEST_RESULTS_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestResultsController';    
    const TEST_START_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestStartController';    
    const TASK_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TaskController';    
    const TEST_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestController';    
    const USER_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\UserController';
    const USER_ACCOUNT_DETAILS_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\UserAccountDetailsController';
    const USER_ACCOUNT_PLAN_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\UserAccountPlanController';
    const REDIRECT_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\RedirectController';
    const STRIPE_EVENT_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\Stripe\EventController';
    
    
    /**
     *
     * @var User
     */
    private $user;
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     */
    protected function setUser(User $user) {
        $this->user = $user;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    protected function getUser() {
        return $this->user;
    }




    /**
     * 
     * @return boolean
     */
    protected function hasUser() {
        return !is_null($this->user);
    }

    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @param array $queryData
     * @return \SimplyTestable\WebClientBundle\Controller\AppController
     */
    protected function getAppController($methodName, $postData = array(), $queryData = array()) {
        return $this->getController(self::APP_CONTROLLER_NAME, $methodName, $postData, $queryData);
    }
    
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @param array $queryData
     * @return \SimplyTestable\WebClientBundle\Controller\TestResultsController
     */
    protected function getTestResultsController($methodName, $postData = array(), $queryData = array()) {
        return $this->getController(self::TEST_RESULTS_CONTROLLER_NAME, $methodName, $postData, $queryData);
    }     
    
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @param array $queryData
     * @return \SimplyTestable\WebClientBundle\Controller\TestProgressController
     */
    protected function getTestProgressController($methodName, $postData = array(), $queryData = array()) {
        return $this->getController(self::TEST_PROGRESS_CONTROLLER_NAME, $methodName, $postData, $queryData);
    }    
    
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @param array $queryData
     * @return \SimplyTestable\WebClientBundle\Controller\TaskController
     */
    protected function getTaskController($methodName, $postData = array(), $queryData = array()) {
        return $this->getController(self::TASK_CONTROLLER_NAME, $methodName, $postData, $queryData);
    }    
    

    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\TestStartController
     */
    protected function getTestStartController($methodName, $postData = array()) {
        return $this->getController(self::TEST_START_CONTROLLER_NAME, $methodName, $postData);
    } 
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\TestController
     */
    protected function getTestController($methodName, $postData = array()) {
        return $this->getController(self::TEST_CONTROLLER_NAME, $methodName, $postData);
    }      


    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\UserController
     */
    protected function getUserController($methodName, $postData = array()) {
        return $this->getController(self::USER_CONTROLLER_NAME, $methodName, $postData);
    }      
    
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\UserAccountDetailsController
     */
    protected function getUserAccountDetailsController($methodName, $postData = array()) {
        return $this->getController(self::USER_ACCOUNT_DETAILS_CONTROLLER_NAME, $methodName, $postData);
    }   
    
    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\UserAccountPlanController
     */
    protected function getUserAccountPlanController($methodName, $postData = array()) {
        return $this->getController(self::USER_ACCOUNT_PLAN_CONTROLLER_NAME, $methodName, $postData);
    }    
    
    
    /**
     *
     * @param string $methodName
     * @return \SimplyTestable\WebClientBundle\Controller\RedirectController
     */
    protected function getRedirectController($methodName, $postData = array(), $queryData = array()) {
        return $this->getController(self::REDIRECT_CONTROLLER_NAME, $methodName, $postData, $queryData);
    }     
   
    /**
     * 
     * @param string $controllerName
     * @param string $methodName
     * @return Symfony\Bundle\FrameworkBundle\Controller\Controller
     */
    protected function getController($controllerName, $methodName, array $postData = array(), array $queryData = array()) {   
        $cookieData = array();
        if ($this->hasUser()) {
            $cookieData['simplytestable-user'] = $this->getUserSerializerService()->serializeToString($this->user);
        }
        
        return $this->createController($controllerName, $methodName, $postData, $queryData, $cookieData);
    }
    
    
    /**
     * 
     * @param string $controllerName
     * @param string $methodName
     * @param string $routeName
     * @param array $postData
     * @param array $queryData
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Controller
     */
    protected function getRoutedController($controllerName, $methodName, $routeName, array $postData = array(), array $queryData = array()) {   
        $cookieData = array();
        if ($this->hasUser()) {
            $cookieData['simplytestable-user'] = $this->getUserSerializerService()->serializeToString($this->user);
        }
        
        return $this->createRoutedController($controllerName, $methodName, $routeName, $postData, $queryData, $cookieData);
    }    

    
    /**
     * 
     * @param string $url
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawler($url, $method = 'GET') {            
        /* @var $this->client \Symfony\Bundle\FrameworkBundle\Client */
        
        if ($this->hasUser()) {
            $cookie = new Cookie(
                    'simplytestable-user',
                    $this->getUserSerializerService()->serializeToString($this->user)
            );
            
            $this->client->getCookieJar()->set($cookie);
        }
        
        $crawler = $this->client->request($method, $url);        
        return $crawler;
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestHttpClientService
     */
    protected function getHttpClientService() {
        return $this->container->get('simplytestable.services.httpclientservice');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestWebResourceService
     */    
    protected function getWebResourceService() {
        return $this->container->get('simplytestable.services.webresourceservice');
    }
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */    
    protected function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }   
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserSerializerService
     */    
    protected function getUserSerializerService() {
        return $this->container->get('simplytestable.services.userserializerservice');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */    
    protected function getUserService() {
        return $this->container->get('simplytestable.services.userservice');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService
     */    
    protected function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
    }      
    
    protected function removeAllTasks() {        
        $tasks = $this->getTaskService()->getEntityRepository()->findAll();        
        foreach ($tasks as $task) {
            $this->getTaskService()->getEntityManager()->remove($task);
        }
        
        $this->getTaskService()->getEntityManager()->flush();
    }      
    
    protected function removeAllTests() {        
        $this->removeAllTasks();
        
        $tests = $this->getTestService()->getEntityRepository()->findAll();
        
        foreach ($tests as $test) {
            $this->getTestService()->getEntityManager()->remove($test);
        }
        
        $this->getTestService()->getEntityManager()->flush();
    }
    
    protected function makeUser() {
        $user = new \SimplyTestable\WebClientBundle\Model\User();
        $user->setUsername('user@example.com');
        $user->setPassword('password');
        return $user;
    }  

}
