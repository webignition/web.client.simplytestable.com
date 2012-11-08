<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use webignition\NormalisedUrl\NormalisedUrl;
use SimplyTestable\WebClientBundle\Model\User;

abstract class BaseController extends Controller
{    
    const DEFAULT_WEBSITE_SCHEME = 'http';    
    
    
    /**
     * 
     * @return boolean
     */
    protected function isProduction() {
        return $this->container->get('kernel')->getEnvironment() == 'prod';
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getUser() {
        return $this->getUserService()->getUser();
    }
    
    /**
     * 
     * @return boolean
     */
    public function isLoggedIn() {
        return !$this->getUserService()->isPublicUser($this->getUser());
    }
    
    
    protected function getRequestValue($name, $default = null, $httpMethod = null) {
        $value = trim($this->get('request')->get($name));        
        if ($value != '') {
            return $value;
        }
        
        $availableHttpMethods = array(
            HTTP_METH_GET,
            HTTP_METH_POST
        );
        
        $defaultHttpMethod = HTTP_METH_GET;
        $requestedHttpMethods = array();
        
        if (is_null($httpMethod)) {
            $requestedHttpMethods = $availableHttpMethods;
        } else {
            if (in_array($httpMethod, $availableHttpMethods)) {
                $requestedHttpMethods[] = $httpMethod;
            } else {
                $requestedHttpMethods[] = $defaultHttpMethod;
            }
        }
        
        foreach ($requestedHttpMethods as $requestedHttpMethod) {
            $requestValues = $this->getRequestValues($requestedHttpMethod);
            if ($requestValues->has($name)) {
                return $requestValues->get($name);
            }
        }
        
        return $default;       
    }
    
    
    /**
     *
     * @param int $httpMethod
     * @return type 
     */
    protected function getRequestValues($httpMethod = HTTP_METH_GET) {
        return ($httpMethod == HTTP_METH_POST) ? $this->getRequest()->request : $this->getRequest()->query;            
    }   
    
    /**
     *
     * @return \webignition\NormalisedUrl\NormalisedUrl
     */
    protected function getNormalisedRequestUrl() {
        $website = $this->getRequestValue('website');
        if (is_null($website)) {
            return $website;
        }
        
        $url = new NormalisedUrl($website);
        if (!$url->hasScheme()) {            
            $url->setScheme(self::DEFAULT_WEBSITE_SCHEME);
        }
        
        return $url;
    }  
    
    
    /**
     * Get the progress page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */
    protected function getProgressUrl($website, $test_id) {
        return $this->generateUrl(
            'app_progress',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
    }
    
    
    /**
     * Get the results page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */    
    protected function getResultsUrl($website, $test_id) {        
        return $this->generateUrl(
            'app_results',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
    }    
    
    
    /**
     * Get the results page URL for a given site, test ID and task ID
     * 
     * @param string $website
     * @param string $test_id
     * @param string $task_id
     * @return string
     */    
    protected function getTaskResultsUrl($website, $test_id, $task_id) {
        return $this->generateUrl(
            'app_task_results',
            array(
                'website' => $website,
                'test_id' => $test_id,
                'task_id' => $task_id
            ),
            true
        );
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    protected function getUserService() {
        return $this->get('simplytestable.services.userservice');
    }
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession() {
        return $this->get('session');
    }   
}
