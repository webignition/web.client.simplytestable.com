<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TestStartController extends TestController
{  
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;
    
    
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
    
    private function startAction($requestValues)
    {        
        $this->getTestService()->setUser($this->getUser());
        
        $this->getTestOptionsAdapter()->setRequestData($requestValues);
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();

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
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');
            $availableTaskTypes = $this->container->getParameter('available_task_types');             
            
            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');
        
            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($availableTaskTypes['default']);
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
            $this->testOptionsAdapter->setInvertInvertableOptions(true);
        }
        
        return $this->testOptionsAdapter;
    }  
}