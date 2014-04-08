<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Model\TestOptions;

class RemoteTestService extends CoreApplicationService {
    
    /**
     *
     * @var RemoteTest
     */
    private $remoteTest = null;
    
    
    /**
     *
     * @var Test
     */
    private $test;
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     */
    public function setTest(Test $test) {        
        $this->test = $test;
        
        if ($this->get()->getId() != $test->getTestId()) {
            $this->remoteTest = null;
        }
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test
     */
    public function getTest() {
        return $this->test;
    }
    
    
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site') {        
        if ($this->hasCustomCookies($testOptions)) {
            $this->setCustomCookieDomainAndPath(
                $testOptions,
                $this->getCookieDomain($canonicalUrl),
                '/'
            );            
        }
        


        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_start', array(
            'canonical-url' => rawurlencode($canonicalUrl)
        )).'?'.http_build_query(array_merge(array(
            'type' => $testType
        ), $testOptions->__toArray())));

        $this->addAuthorisationToRequest($httpRequest);

        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($httpRequest);
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            throw $curlException;
        }
    } 
    
    
    /**
     * 
     * @param string $canonicalUrl
     * @return string
     */
    private function getCookieDomain($canonicalUrl) {        
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        return $parser->parseUrl($canonicalUrl)->host->registerableDomain;
    }
    
    
    private function setCustomCookieDomainAndPath(TestOptions $testOptions, $domain, $path) {                
        $cookieOptions = $testOptions->getFeatureOptions('cookies');        
        $cookies = $cookieOptions['cookies'];
        
        foreach ($cookies as $index => $cookie) {
            if (!isset($cookie['path'])) {
                $cookie['path'] = '/';
            }
            
            if (!isset($cookie['domain'])) {
                $cookie['domain'] = '.' . $domain;
            }
            
            $cookies[$index] = $cookie;
        }
        
        $cookieOptions['cookies'] = $cookies;        
        $testOptions->setFeatureOptions('cookies', $cookieOptions);
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\TestOptions $testOptions
     * @return boolean
     */
    private function hasCustomCookies(TestOptions $testOptions) {
        if (!$testOptions->hasFeatureOptions('cookies')) {
            return;
        }   
        
        $cookieOptions = $testOptions->getFeatureOptions('cookies');
        $cookies = $cookieOptions['cookies'];
        
        foreach ($cookies as $cookie) {
            if (isset($cookie['name']) && !is_null($cookie['name'])) {
                return true;
            }
        }
        
        return false;       
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    public function authenticate() {
        if ($this->owns()) {
            return true;
        }
        
        return $this->isPublic();
    }     
    
    
    /**
     * 
     * @return boolean
     * @throws \SimplyTestable\WebClientBundle\Services\WebResourceException
     */
    public function owns() {
        if ($this->getUser()->getUsername() == $this->getTest()->getUser()) {
            return true;
        }
        
        try {
            return $this->getUser()->getUsername() == $this->get()->getUser();         
        } catch (WebResourceException $webResourceException) {            
            if ($webResourceException->getCode() == 403) {
                return false;
            }
            
            throw $webResourceException;
        }
    } 
    
    
    /**
     * 
     * @return boolean
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     * @throws \SimplyTestable\WebClientBundle\Services\CurlException
     */
    public function isPublic() {        
        return $this->get()->getIsPublic();
    }
    
    
    
    /**
     *
     * @return \stdClass|boolean 
     */
    public function get() {        
        if (is_null($this->remoteTest)) {            
            $remoteJsonDocument = $this->retrieve();
            
            if ($remoteJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                $this->remoteTest = new RemoteTest($remoteJsonDocument->getContentObject());
            } else {
                $this->remoteTest = false;
            }
        }
        
        return $this->remoteTest;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function has() {
        if (is_null($this->remoteTest)) {
            return false;
        }
        
        if (is_null($this->test)) {
            return false;
        }
        
        return $this->get()->getId() == $this->getTest()->getTestId();
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest $remoteTest
     */
    public function set(RemoteTest $remoteTest) {
        $this->remoteTest = $remoteTest;
    }
    
    
    /**
     * 
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     * @throws WebResourceException
     * @throws \Guzzle\Http\Exception\CurlException
     */
    private function retrieve() {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_status', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);   
    } 
    
    
    /**
     *
     * @param Test $test
     * @param int $testId
     * @return boolean 
     */
    public function cancel() {        
        return $this->cancelByTestProperties($this->getTest()->getTestId(), $this->getTest()->getWebsite());      
    }
    

    /**
     * 
     * @param int $testId
     * @param string $website
     * @return boolean
     */
    public function cancelByTestProperties($testId, $website) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_cancel', array(
            'canonical-url' => urlencode($website),
            'test_id' => $testId
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);          
    }     
    
    
    /**
     * 
     * @param int $testId
     * @param string $website
     * @return boolean
     */
    public function retest($testId, $website) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_retest', array(
            'canonical-url' => urlencode($website),
            'test_id' => $testId
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);          
    }    
    
    
    
    public function lock() {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_set_private', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);       
        return true;
    }
    
    
    public function unlock() {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_set_public', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);       
        return true;        
    } 
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    public function getCurrent() {
        $requestUrl = $this->getUrl('tests_list', array(
            'limit' => 100,
            'offset' => 0
        )) . '?exclude-finished=1';
        
        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
        
        return $this->getList($request);        
    }  
    
    
    /**
     * 
     * @param int $limit
     * @param int $offset 
     * @param string $filter
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    public function getFinished($limit, $offset, $filter = null) {
        $requestUrl = $this->getUrl('tests_list', array(
            'limit' => $limit,
            'offset' => $offset
        ));
        
        $query = array(
            'exclude-states' => array('rejected'),
            'exclude-current' => 1
        );
        
        if (!is_null($filter)) {
            $query['url-filter'] = $filter;
        }
        
        $requestUrl .= '?' . http_build_query($query);
        
        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
        
        return $this->getList($request);        
    } 
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    private function getList(\Guzzle\Http\Message\Request $request) {        
        $this->addAuthorisationToRequest($request);
        
        $list = new TestList();
        
        try {
            /* @var $responseDocument \webignition\WebResource\JsonDocument\JsonDocument */
            $responseDocument = $this->webResourceService->get($request);
            
            $list->setMaxResults($responseDocument->getContentObject()->max_results);
            $list->setLimit($responseDocument->getContentObject()->limit);
            $list->setOffset($responseDocument->getContentObject()->offset);
            
            foreach ($responseDocument->getContentObject()->jobs as $remoteTestData) {
                $list->addRemoteTest(new RemoteTest($remoteTestData));
            }
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
        }        
        
        return $list;          
    }     
    
    
    
    public function retrieveLatest($canonicalUrl) {                
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_latest', array(
            'canonical-url' => urlencode($canonicalUrl)
        )));
        
        $this->addAuthorisationToRequest($request);        
        
        /* @var $testJsonDocument \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $remoteJsonDocument = $this->webResourceService->get($request);           
            
            if ($remoteJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                return new RemoteTest($remoteJsonDocument->getContentObject());
            }
            
            return false;
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return null;
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            if ($webResourceException->getCode() == 403) {
                return false;
            }
        }
        
        return null;          
    }    
    
}