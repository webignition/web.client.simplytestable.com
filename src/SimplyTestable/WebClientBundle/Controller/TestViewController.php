<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;

class TestViewController extends BaseViewController
{

    /**
     * 
     * @param string $website
     * @param int $test_id
     * @return \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome
     */
    protected function getTestRetrievalOutcome($website, $test_id) {        
        $outcome = new \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome();
        
        try {
            if (!$this->getTestService()->has($website, $test_id)) {
                $outcome->setResponse( $this->redirect($this->generateUrl('app_test_redirector', array(
                    'website' => $website,
                    'test_id' => $test_id
                ), true)));
                return $outcome;
            }            
            
            $test = $this->getTestService()->get($website, $test_id);
            if ($this->getTestService()->getRemoteTestService()->authenticate()) {           
                $outcome->setTest($test);
                return $outcome;
            }             
            
        } catch (WebResourceException $webResourceException) {
            if ($webResourceException->getCode() != 403) {
                throw $webResourceException;
            }
        }

        if ($this->isLoggedIn()) {
            $this->setTemplate('SimplyTestableWebClientBundle:App:test-not-authorised.html.twig');
            $outcome->setResponse($this->sendResponse(array(
                'this_url' => $this->getProgressUrl($website, $test_id),
                'test_input_action_url' => $this->generateUrl('test_cancel', array(
                    'website' => $website,
                    'test_id' => $test_id
                )),
                'website' => $website,
                'test_id' => $test_id,
                'public_site' => $this->container->getParameter('public_site'),
                'user' => $this->getUser(),
                'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                
            )));
            
            return $outcome;
        }
        
        $redirectParameters = json_encode(array(
            'route' => 'app_progress',
            'parameters' => array(
            'website' => $website,
            'test_id' => $test_id                        
            )
        ));

        $this->get('session')->setFlash('user_signin_error', 'test-not-logged-in');

        $outcome->setResponse($this->redirect($this->generateUrl('sign_in', array(
            'redirect' => base64_encode($redirectParameters)
        ), true)));
        
        return $outcome;
    } 
    
    
    /**
     * 
     * @param string $website
     * @return string[]
     */
    protected function getWebsiteViewValues($website = null) {        
        if (is_null($website) || trim($website) === '') {
            return array();
        }
        
        $websiteUrl = new \webignition\NormalisedUrl\NormalisedUrl($website);
        $websiteUrl->getConfiguration()->enableConvertIdnToUtf8();
        
       $utf8Schemeless = $this->getSchemelessUrl($websiteUrl);
       $utf8SchemelessTruncated = $this->getTruncatedString($utf8Schemeless, 64);
       
       return array(
           'raw' => $website,
           'utf8' => array(
               'raw' => (string)$websiteUrl,
               'schemeless' => array(
                   'raw' => $utf8Schemeless,
                   'truncated' => $utf8SchemelessTruncated,
                   'is_truncated' => ($utf8Schemeless != $utf8SchemelessTruncated)
               )
           )
       );
    }
    
    private function getTruncatedString($input, $maxLength = 128) {
        if (mb_strlen($input) <= $maxLength) {
            return $input;
        }
        
        return mb_substr($input, 0, $maxLength);
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
     * @param string $url
     * @return string
     */
    protected function getSchemelessUrl($url) {       
        $schemeMarkers = array(
            'http://',
            'https://'
        );
        
        foreach ($schemeMarkers as $schemeMarker) {            
            if (substr($schemeMarker, 0, strlen($schemeMarker)) == $schemeMarker) {                
                return substr($url, strlen($schemeMarker));
            }
        }
        
        return $url;
    }
    
}
