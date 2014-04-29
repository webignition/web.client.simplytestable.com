<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable as CacheableController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered as IEFilteredController;

use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;

class RequestListener
{   
    const APPLICATION_CONTROLLER_PREFIX = 'SimplyTestable\WebClientBundle\Controller\\';    
    
    /**
     *
     * @var GetResponseEvent 
     */
    private $event;
    
    
    /**
     *
     * @var Kernel
     */
    private $kernel;
    
    
    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Controller\Controller 
     */
    private $controller;
    
    
    /**
     * 
     * @param \Symfony\Component\HttpKernel\Kernel $kernel
     */
    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
    }    

    
    /**
     * 
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @return null
     */
    public function onKernelRequest(GetResponseEvent $event) {         
        $this->event = $event;      
        
        if (!$this->isApplicationController()) {
            return;
        }
        
        if ($this->isIeFilteredController() && $this->isUsingOldIE()) {
            $this->event->setResponse($this->getRedirectResponseToOutdatedBrowserPage());
        }
        
        $this->getUserService()->setUserFromRequest($this->event->getRequest());
        
        if (!$this->isCacheableController()) {
            return;
        }
        
        $this->setRequestCacheValidatorHeaders();
      
        $response = $this->getCacheableResponseService()->getCachableResponse($this->event->getRequest());
        
        $this->fixRequestIfNoneMatchHeader();        
        
        if ($response->isNotModified($this->event->getRequest())) {
            $this->event->setResponse($response);
            $this->kernel->getContainer()->get('session')->getFlashBag()->clear();
        } 
    }
    
    
    private function setRequestCacheValidatorHeaders() { 
        $this->getController()->setRequest($this->event->getRequest());        
        $cacheValidatorParameters = $this->getController()->getCacheValidatorParameters();
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier($cacheValidatorParameters);
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $this->event->getRequest()->headers->set('x-cache-validator-etag', $cacheValidatorHeaders->getETag());
        $this->event->getRequest()->headers->set('x-cache-validator-lastmodified', $cacheValidatorHeaders->getLastModifiedDate()->format('c'));       
    }
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getRedirectResponseToOutdatedBrowserPage() {        
        return new RedirectResponse($this->kernel->getContainer()->getParameter('public_site')['urls']['home']);
    }
    
    
    private function fixRequestIfNoneMatchHeader() {
        $currentIfNoneMatch = $this->event->getRequest()->headers->get('if-none-match');
        
        $modifiedEtag = preg_replace('/-gzip"$/', '"', $currentIfNoneMatch);
        
        $this->event->getRequest()->headers->set('if-none-match', $modifiedEtag);          
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isIeFilteredController() {        
        return $this->getController() instanceof IEFilteredController;   
    }
    
    
    /**
     * 
     * @return string
     */
    private function getControllerClassName() {
        $controllerActionParts = explode('::', $this->event->getRequest()->attributes->get('_controller'));        
        return $controllerActionParts[0];    
    }
    
    

    /**
     * 
     * @return boolean
     */
    private function isApplicationController() {          
        return substr($this->getControllerClassName(), 0, strlen(self::APPLICATION_CONTROLLER_PREFIX)) == self::APPLICATION_CONTROLLER_PREFIX;
    }
    
    
    /**
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Controller 
     */
    private function getController() {
        if (is_null($this->controller)) {
            $className = $this->getControllerClassName();
            $this->controller = new $className;
            $this->controller->setContainer($this->kernel->getContainer());
        }
        
        return $this->controller;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isCacheableController() {
        return $this->getController() instanceof CacheableController; 
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingOldIE() {        
        if ($this->isUsingIE6() || $this->isUsingIE7()) {
            $this->kernel->getContainer()->get('logger')->err('Detected old IE for [' . $this->getUserAgentString() . ']');
            return true;
        }
        
        return false;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingIE6() {       
        // // Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)
        
        if (!preg_match('/msie 6\.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }
        
        if (preg_match('/opera/i', $this->getUserAgentString())) {
            return false;
        }
        
        if (preg_match('/msie 8.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingIE7() {        
        if (!preg_match('/msie 7\.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }
        
        return true;
    } 
    
    
    /**
     * 
     * @return string
     */
    private function getUserAgentString() {
        return $this->event->getRequest()->server->get('HTTP_USER_AGENT');
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService 
     */
    private function getCacheValidatorHeadersService() {
        return $this->kernel->getContainer()->get('simplytestable.services.cachevalidatorheadersservice');
    }     
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier 
     */
    private function getCacheValidatorIdentifier(array $parameters = array()) {        
        if (!$this->isCacheableController()) {
            return null;
        }
        
        $identifier = new CacheValidatorIdentifier();
        $identifier->setParameter('route', $this->event->getRequest()->get('_route'));
        $identifier->setParameter('user', $this->getUserService()->getUser()->getUsername());
        $identifier->setParameter('is_logged_in', $this->getUserService()->isLoggedIn());
        
        foreach ($parameters as $key => $value) {
            $identifier->setParameter($key, $value);
        }
        
        return $identifier;
    }
    

    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    private function getUserService() {
        return $this->kernel->getContainer()->get('simplytestable.services.userservice');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\CacheableResponseService
     */
    private function getCacheableResponseService() {
        return $this->kernel->getContainer()->get('simplytestable.services.cacheableResponseService');
    }    

}