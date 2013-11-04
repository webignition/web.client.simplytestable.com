<?php
namespace SimplyTestable\WebClientBundle\Services;

//use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
//use SimplyTestable\WebClientBundle\Entity\Task\Task;
//use SimplyTestable\WebClientBundle\Entity\TimePeriod;
//use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;
//use SimplyTestable\WebClientBundle\Model\User;
//use SimplyTestable\WebClientBundle\Exception\UserServiceException;
//use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestOptions;

use webignition\NormalisedUrl\NormalisedUrl;


class RemoteTestService extends CoreApplicationService {
    
    /**
     *
     * @var RemoteTest
     */
    private $remoteTest = null;
    
    
    /**
     *
     * @var int
     */
    private $remoteTestId = null;
    
    
    /**
     *
     * @var string
     */
    private $remoteTestCanonicalUrl = null;
    
    
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
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test
     */
    public function getTest() {
        return $this->test;
    }
    
    
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site') {                
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
            $remoteTestSummaryJsonDocument = $this->retrieve();
            if ($remoteTestSummaryJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                $this->remoteTest = new RemoteTest($remoteTestSummaryJsonDocument->getContentObject());
            } else {
                $this->remoteTest = false;
            }
        }
        
        return $this->remoteTest;
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
    
}