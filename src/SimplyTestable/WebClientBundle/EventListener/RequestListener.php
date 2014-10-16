<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser as RequiresPrivateUserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser as RequiresValidUserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner as RequiresValidTestOwnerController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest as RequiresCompletedTestController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable as CacheableController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered as IEFilteredController;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

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
     * @param GetResponseEvent $event
     * @throws \Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function onKernelRequest(GetResponseEvent $event) {
        if ($event->getRequestType() == HttpKernelInterface::SUB_REQUEST) {
            return;
        }

        $this->event = $event;
        
        if (!$this->isApplicationController()) {
            return;
        }

        if ($this->isIeFilteredController() && $this->isUsingOldIE()) {
            $this->event->setResponse($this->getRedirectResponseToOutdatedBrowserPage());
        }

        $this->getUserService()->setUserFromRequest($this->event->getRequest());

        try {
            if ($this->isCacheableController()) {
                $this->setRequestCacheValidatorHeaders();

                $response = $this->getCacheableResponseService()->getCachableResponse($this->event->getRequest());

                $this->fixRequestIfNoneMatchHeader();

                if ($response->isNotModified($this->event->getRequest())) {
                    $this->event->setResponse($response);
                    $this->kernel->getContainer()->get('session')->getFlashBag()->clear();
                    return;
                }
            }
        } catch (\Exception $e) {
            // Exceptions may well be raised by a controller when getting cache validator parameters as the controller
            // can assume user and test authentication has already been carried out prior to the request reaching the
            // controller.
            // It is safe to ignore such exceptions.
            // It is much faster to try and return a 304 Not Modified response at this stage prior to any significant
            // processing occurring.
        }

        if ($this->isRequiresValidUserController() && !$this->getUserService()->authenticate()) {
            $this->event->setResponse(new RedirectResponse($this->getController()->generateUrl('sign_out_submit', array(), true)));
            return;
        }

        if ($this->isRequiresValidTestOwnerController()) {
            $website = $this->event->getRequest()->attributes->get('website');
            $test_id = $this->event->getRequest()->attributes->get('test_id');

            try {
                if (!$this->getTestService()->has($website, $test_id)) {
                    $this->event->setResponse($this->getController()->getInvalidOwnerResponse());
                    return;
                }

                $this->getTestService()->get($website, $test_id);
            } catch (WebResourceException $webResourceException) {
                if ($webResourceException->getCode() == 403) {
                    $this->event->setResponse($this->getController()->getInvalidOwnerResponse());
                    return;
                }

                throw $webResourceException;
            }
        }

        if ($this->isRequiresCompletedTestController()) {
            $this->getController()->setRequest($this->event->getRequest());
            $test = $this->getTest();

            if ($test->getState() == 'failed-no-sitemap') {
                $this->event->setResponse($this->getController()->getFailedNoSitemapTestResponse());
                return;
            }

            if ($test->getState() == 'rejected') {
                $this->event->setResponse($this->getController()->getRejectedTestResponse());
                return;
            }

            if (!$this->getTestService()->isFinished($test)) {
                $this->event->setResponse($this->getController()->getNotFinishedTestResponse());
                return;
            }

            if ($this->getTest()->getWebsite() != $this->event->getRequest()->attributes->get('website')) {
                $this->event->setResponse($this->getController()->getRequestWebsiteMismatchResponse());
                return;
            }
        }

        if ($this->isRequiresPrivateUserController() && !$this->getUserService()->isLoggedIn()) {
            $this->kernel->getContainer()->get('session')->getFlashBag()->set('user_signin_error', 'account-not-logged-in');
            $this->event->setResponse($this->getController()->getUserSignInRedirectResponse());
            return;
        }
    }
    
    
    private function setRequestCacheValidatorHeaders() { 
        $this->getController()->setRequest($this->event->getRequest());
        $cacheValidatorParameters = $this->getController()->getCacheValidatorParameters();

        if ($this->event->getRequest()->headers->has('accept')) {
            $cacheValidatorHeaders['http-header-accept'] = $this->event->getRequest()->headers->get('accept');
        }
        
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
     * @return boolean
     */
    private function isRequiresPrivateUserController() {
        return $this->getController() instanceof RequiresPrivateUserController;
    }


    /**
     * @return boolean
     */
    private function isRequiresValidUserController() {
        return $this->getController() instanceof RequiresValidUserController or $this->isRequiresPrivateUserController();
    }


    /**
     * @return boolean
     */
    private function isRequiresValidTestOwnerController() {
        return $this->getController() instanceof RequiresValidTestOwnerController;
    }


    /**
     * @return boolean
     */
    private function isRequiresCompletedTestController() {
        return $this->getController() instanceof RequiresCompletedTestController;
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
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        return $this->kernel->getContainer()->get('simplytestable.services.testservice');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\CacheableResponseService
     */
    private function getCacheableResponseService() {
        return $this->kernel->getContainer()->get('simplytestable.services.cacheableResponseService');
    }


    /**
     * @return Test
     */
    private function getTest() {
        return $this->getTestService()->get(
            $this->event->getRequest()->attributes->get('website'),
            $this->event->getRequest()->attributes->get('test_id')
        );
    }

}