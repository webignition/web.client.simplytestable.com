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

        $outcome->setResponse($this->redirect($this->generateUrl('user_view_signin', array(
            'redirect' => base64_encode($redirectParameters)
        ), true)));
        
        return $outcome;
    } 
    
    
    /**
     * 
     * @param string $url
     * @return string[]
     */
    protected function getUrlViewValues($url = null) {        
        if (is_null($url) || trim($url) === '') {
            return array();
        }
        
        $websiteUrl = new \webignition\NormalisedUrl\NormalisedUrl($url);
        $websiteUrl->getConfiguration()->enableConvertIdnToUtf8();
        
        $utf8Raw = (string)$websiteUrl;
        $utf8Truncated_40 = $this->getTruncatedString($utf8Raw, 40);
        $utf8Truncated_50 = $this->getTruncatedString($utf8Raw, 50);
        $utf8Truncated_64 = $this->getTruncatedString($utf8Raw, 64);
        
        $utf8Schemeless = $this->trimUrl($utf8Raw);
        
        $utf8SchemelessTruncated_40 = $this->getTruncatedString($utf8Schemeless, 40);
        $utf8SchemelessTruncated_50 = $this->getTruncatedString($utf8Schemeless, 50);
        $utf8SchemelessTruncated_64 = $this->getTruncatedString($utf8Schemeless, 64);
       
        return array(
            'raw' => $url,
            'utf8' => array(
                'raw' => $utf8Raw,
                'truncated_40' => $utf8Truncated_40,
                'truncated_50' => $utf8Truncated_50,
                'truncated_64' => $utf8Truncated_64,
                'is_truncated_40' => ($utf8Raw != $utf8Truncated_40),
                'is_truncated_50' => ($utf8Raw != $utf8Truncated_50),
                'is_truncated_64' => ($utf8Raw != $utf8Truncated_64),
                'schemeless' => array(
                    'raw' => $utf8Schemeless,
                    'truncated_40' => $utf8SchemelessTruncated_40,
                    'truncated_64' => $utf8SchemelessTruncated_64,
                    'is_truncated_40' => ($utf8Schemeless != $utf8SchemelessTruncated_40),
                    'is_truncated_50' => ($utf8Schemeless != $utf8SchemelessTruncated_50),
                    'is_truncated_64' => ($utf8Schemeless != $utf8SchemelessTruncated_64)
                 )
            )
        );
    }
    
    private function trimUrl($url) {
        $url = $this->getSchemelessUrl($url);
        
        if (substr($url, strlen($url) - 1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }
        
        return $url;
    }
    
    private function getTruncatedString($input, $maxLength = 64) {
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
