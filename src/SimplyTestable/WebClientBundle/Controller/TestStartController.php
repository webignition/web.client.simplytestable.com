<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TestStartController extends TestController
{
    
    
    /**
     * Names of inputs where the value should be inverted (boolean)
     * 
     * @var array
     */
    private $invertOptionKeys = array(
        'js-static-analysis-jslint-option-bitwise',
        'js-static-analysis-jslint-option-continue',
        'js-static-analysis-jslint-option-debug',
        'js-static-analysis-jslint-option-evil',
        'js-static-analysis-jslint-option-eqeq',
        'js-static-analysis-jslint-option-es5',        
        'js-static-analysis-jslint-option-forin',
        'js-static-analysis-jslint-option-newcap',
        'js-static-analysis-jslint-option-nomen',
        'js-static-analysis-jslint-option-plusplus',
        'js-static-analysis-jslint-option-regexp',
        'js-static-analysis-jslint-option-undef',
        'js-static-analysis-jslint-option-unparam',
        'js-static-analysis-jslint-option-sloppy',
        'js-static-analysis-jslint-option-stupid',
        'js-static-analysis-jslint-option-sub',
        'js-static-analysis-jslint-option-vars',
        'js-static-analysis-jslint-option-white',
        'js-static-analysis-jslint-option-anon'
    );
    
    private $jsLintOptionsToInclude = array(
        'js-static-analysis-jslint-option-passfail',
        'js-static-analysis-jslint-option-bitwise',
        'js-static-analysis-jslint-option-continue',
        'js-static-analysis-jslint-option-debug',
        'js-static-analysis-jslint-option-evil',
        'js-static-analysis-jslint-option-eqeq',
        'js-static-analysis-jslint-option-es5',
        'js-static-analysis-jslint-option-forin',
        'js-static-analysis-jslint-option-newcap',
        'js-static-analysis-jslint-option-nomen',
        'js-static-analysis-jslint-option-plusplus',
        'js-static-analysis-jslint-option-regexp',
        'js-static-analysis-jslint-option-undef',
        'js-static-analysis-jslint-option-unparam',
        'js-static-analysis-jslint-option-sloppy',
        'js-static-analysis-jslint-option-stupid',
        'js-static-analysis-jslint-option-sub',
        'js-static-analysis-jslint-option-vars',
        'js-static-analysis-jslint-option-white',
        'js-static-analysis-jslint-option-anon'        
    );
    
    
    
    public function startNewAction()
    {        
        $this->getTestService()->setUser($this->getUser());        
        return $this->startAction($this->getRequestValues(\Guzzle\Http\Message\Request::POST));
    }
    
    
    public function cloneAndStartAction($website, $test_id) {        
        try {
            $this->getTestService()->get($website, $test_id, $this->getUser());            
            $testRequestData = $this->translateRemoteTestSummaryToRequestData($this->getTestService()->getRemoteTestSummary());
            $testRequestData->add(array(
                'full-single' => $this->getRequestValue('full-single')
            ));
            
            return $this->startAction($testRequestData);           
        } catch (UserServiceException $e) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }       
    }

    
    public function crawlAction($website, $test_id) {       
        try {            
            $this->getTestService()->startCrawl($this->getTestService()->get($website, $test_id, $this->getUser()));
            
            return $this->redirect($this->generateUrl(
                'crawl_progress',
                array(
                    'website' => $website,
                    'test_id' => $test_id
                ),
                true
            ));           
        } catch (UserServiceException $e) {
            return $this->redirect($this->generateUrl('app', array(), true));
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $clientErrorResponseException) {
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $website,
                    'test_id' => $test_id
                ),
                true
            ));        
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $website,
                    'test_id' => $test_id
                ),
                true
            ));  
        }        
    }
    
    private function invertInvestableOptions(\Symfony\Component\HttpFoundation\ParameterBag $requestValues) {
        foreach ($this->invertOptionKeys as $optionName) {
            if ($requestValues->has($optionName)) {                
                $requestValues->set($optionName, ($requestValues->get($optionName) == '1') ? '0' : '1');
            } else {
                $requestValues->set($optionName, '1');
            }
        }
    }
    
    private function excludeJsLintOptions(\Symfony\Component\HttpFoundation\ParameterBag $requestValues) {
        foreach ($requestValues as $key => $value) {
            if (substr_count($key, 'js-static-analysis-jslint-option') && !in_array($key, $this->jsLintOptionsToInclude)) {
                $requestValues->remove($key);
            }
            
            
        }
        
        //var_dump($requestValues);
        //exit();
        
//        foreach ($this->invertOptionKeys as $optionName) {
//            if ($requestValues->has($optionName)) {                
//                $requestValues->set($optionName, ($requestValues->get($optionName) == '1') ? '0' : '1');
//            }
//        }
    }

    
    private function startAction($requestValues)
    {           
        ini_set('xdebug.var_display_max_depth', 8);
        
        $this->getTestService()->setUser($this->getUser());
        
        $this->excludeJsLintOptions($requestValues);        
        $this->invertInvestableOptions($requestValues);
        
//        var_dump($requestValues);
//        exit();        
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestValues);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
//        var_dump($testOptions);
//        exit();

        if (!$this->hasWebsite()) {            
            $this->get('session')->setFlash('test_start_error', 'website-blank');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        }
        
        if ($this->getWebsiteBlockListService()->contains($this->getWebsite())) {
            $this->get('session')->setFlash('test_start_error', 'website-blocked');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));            
        }
        
        if ($testOptions->hasTestTypes() === false) {
            $this->get('session')->setFlash('test_start_error', 'no-test-types-selected');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));                
        }
        
        try {
            $jsonResponseObject = $this->getTestService()->start($this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'))->getContentObject();
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
            $this->getTestQueueService()->enqueue($this->getUser(), $this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'), $webResourceException->getResponse()->getStatusCode());
            return $this->redirect($this->generateUrl(
                'app_website',
                array(
                    'website' => $this->getTestUrl()                        
                ),
                true
            ));
        }
    }    
    
    
    /**
     * 
     * @param array $remoteTestSummary
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function translateRemoteTestSummaryToRequestData($remoteTestSummary) {    
        $requestData = array(
            'website' => $remoteTestSummary->website
        );
        
        foreach ($remoteTestSummary->task_types as $taskType) {
            $requestData[strtolower(str_replace(' ', '-', $taskType->name))] = "1";
        }

        foreach ($remoteTestSummary->task_type_options as $taskType => $taskTypeOptionSet) {
            foreach ($taskTypeOptionSet as $key => $value) {
                $requestData[strtolower(str_replace(' ', '-', $taskType)) . '-' . $key] = (is_array($value)) ? implode("\r\n", $value) : $value;
            }            

        }
        
        return new \Symfony\Component\HttpFoundation\ParameterBag($requestData); 
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
        
        return $redirectValues;
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\WebsiteBlockListService
     */
    private function getWebsiteBlockListService() {
        $websiteBlockListService = $this->get('simplytestable.services.websiteblocklistservice');
        $websiteBlockListService->setBlockListResourcePath($this->container->get('kernel')->locateResource('@SimplyTestableWebClientBundle/Resources/config/WebsiteBlockList.txt'));
        
        return $websiteBlockListService;
    }    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    }  
}