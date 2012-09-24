<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;

use webignition\NormalisedUrl\NormalisedUrl;

abstract class BaseController extends Controller
{    
    protected function getRequestValue($name, $default = null, $httpMethod = null) {
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
    
}
