<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseViewController
{ 
    
    public function signInAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $templateName = 'SimplyTestableWebClientBundle:User:index.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        //$hasTestStartError = $this->hasFlash('test_start_error');
        //$hasTestStartBlockedWebsiteError = $this->hasFlash('test_start_error_blocked_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        //$cacheValidatorIdentifier->setParameter('test_start_error', ($hasTestStartError) ? 'true' : 'false');
        //$cacheValidatorIdentifier->setParameter('test_start_error_blocked_website', ($hasTestStartBlockedWebsiteError) ? 'true' : 'false');
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);

        return $this->getCachableResponse($this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            //'test_start_error' => $hasTestStartError,
            //'test_start_error_blocked_website' => $hasTestStartBlockedWebsiteError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser())
        )), $cacheValidatorHeaders);        
    }

}
