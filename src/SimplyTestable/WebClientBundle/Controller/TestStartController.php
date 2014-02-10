<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class TestStartController extends TestController
{  
    const HTTP_AUTH_FEATURE_NAME = 'http-authentication';    
    const HTTP_AUTH_FEATURE_USERNAME_KEY = 'http-auth-username';
    const HTTP_AUTH_FEATURE_PASSWORD_KEY = 'http-auth-password';
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;
    
    
    public function startNewAction()
    {  
        $requestValues = $this->getRequestValues(\Guzzle\Http\Message\Request::POST);
        
        if (!$requestValues->has('link-integrity-excluded-domains') && $this->container->hasParameter('link-integrity-excluded-domains')) {
            $requestValues->set('link-integrity-excluded-domains', $this->container->getParameter('link-integrity-excluded-domains'));
        }        
        
        return $this->startAction($requestValues);
    }
    
    private function startAction(\Symfony\Component\HttpFoundation\ParameterBag $requestValues)
    {        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        $this->getTestOptionsAdapter()->setRequestData($requestValues);
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();
        
        if ($testOptions->hasFeatureOptions(self::HTTP_AUTH_FEATURE_NAME)) {
            $httpAuthFeatureOptions = $testOptions->getFeatureOptions(self::HTTP_AUTH_FEATURE_NAME);
            
            if ($httpAuthFeatureOptions[self::HTTP_AUTH_FEATURE_USERNAME_KEY] == '' && $httpAuthFeatureOptions[self::HTTP_AUTH_FEATURE_PASSWORD_KEY] == '') {
                $testOptions->removeFeatureOptions(self::HTTP_AUTH_FEATURE_NAME);
                $httpAuthFeatureOptions = $testOptions->getFeatureOptions(self::HTTP_AUTH_FEATURE_NAME);
            }            
        }

        if (!$this->hasWebsite()) {            
            $this->get('session')->setFlash('test_start_error', 'website-blank');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        }
        
        if ($testOptions->hasTestTypes() === false) {
            $this->get('session')->setFlash('test_start_error', 'no-test-types-selected');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));                
        }
        
        try {
            $jsonResponseObject = $this->getTestService()->getRemoteTestService()->start($this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'))->getContentObject();            
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $jsonResponseObject->website,
                    'test_id' => $jsonResponseObject->id
                ),
                true
            ));
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->get('session')->setFlash('test_start_error', 'curl-error');
            $this->get('session')->setFlash('curl_error_code', $curlException->getErrorNo());
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {                            
            $this->get('session')->setFlash('test_start_error', 'web_resource_exception');
            
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        }
    }
    
    
    /**
     * 
     * @return type
     */
    private function getTestUrl() {
        if ($this->isFullTest()) {
            return $this->getCanonicalUrlFromWebsite($this->getWebsite());
        }
        
        $url = new \webignition\NormalisedUrl\NormalisedUrl($this->getWebsite());
        return (string)$url;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isFullTest() {
        /* @var $requestParameters \Symfony\Component\HttpFoundation\ParameterBag */
        $requestParameters = $this->getRequestValues(\Guzzle\Http\Message\Request::POST);
        if (!$requestParameters->has('full-single')) {
            return true;
        }
        
        return $requestParameters->get('full-single') == 'full';
    }
    
    
    /**
     * 
     * @param string $website
     * @return string
     */
    private function getCanonicalUrlFromWebsite($website) {
        $url = new \webignition\NormalisedUrl\NormalisedUrl($website);
        $url->setFragment(null);
        $url->setPath('/');
        $url->setQuery(null);
        
        return (string)$url;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\TestOptions $testOptions
     * @return array
     */
    private function getRedirectValues(TestOptions $testOptions) {
        $redirectValues = array();
        
        if ($this->hasWebsite()) {
            $redirectValues['website'] = trim($this->getRequestValue('website'));
        }
        
        $absoluteTestTypes = $testOptions->getAbsoluteTestTypes();        
        
        foreach ($absoluteTestTypes as $testTypeKey => $selectedValue) {
            $redirectValues[$testTypeKey] = $selectedValue;
            $redirectValues = array_merge($redirectValues, $testOptions->getAbsoluteTestTypeOptions($testTypeKey));
        }
        
        $absoluteFeatures = $testOptions->getAbsoluteFeatures();
        foreach ($absoluteFeatures as $featureKey => $selectedValue) {            
            $featureOptions = $testOptions->getAbsoluteFeatureOptions($featureKey);
            
            foreach ($featureOptions as $optionKey => $optionValue) {
                if (!$this->isIgnoredOnRedirect($optionKey)) {
                    $redirectValues[$optionKey] = $optionValue;   
                }                 
            }                       
        }
        
        return $redirectValues;
    }
    
    
    /**
     * 
     * @param string $formKey
     * @return boolean
     */
    private function isIgnoredOnRedirect($formKey) {
        $testOptionsParameters = $this->container->getParameter('test_options'); 
        if (!isset($testOptionsParameters['ignore_on_error'])) {
            return false;
        }
        
        //var_dump("cp01", $formKey, $testOptionsParameters['ignore_on_error']);
        
        return in_array($formKey, $testOptionsParameters['ignore_on_error']);
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');         
            
            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');
        
            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getAvailableTaskTypes());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
            $this->testOptionsAdapter->setInvertInvertableOptions(true);
            
            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }            
        }
        
        return $this->testOptionsAdapter;
    } 
    
    
    /**
     * 
     * @return array
     */
    private function getAvailableTaskTypes() {
        $this->getAvailableTaskTypeService()->setUser($this->getUser());
        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());
        
        return $this->getAvailableTaskTypeService()->get();    
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }       
}