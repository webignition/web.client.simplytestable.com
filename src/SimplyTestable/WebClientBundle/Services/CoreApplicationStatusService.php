<?php
namespace SimplyTestable\WebClientBundle\Services;

class CoreApplicationStatusService extends CoreApplicationService { 
    
    private $remoteCoreApplicationSummary = null;
    
    
    /**
     * 
     * @return string
     */
    public function getState() {        
        return $this->getRemoteCoreApplicationSummaryProperty('state');
    }
    
    
    /**
     * 
     * @return int
     */
    public function getTaskThroughputPerMinute() {
        return $this->getRemoteCoreApplicationSummaryProperty('task_throughput_per_minute');
    }
    

    /**
     * 
     * @return int
     */
    public function getInProgressJobCount() {
        return $this->getRemoteCoreApplicationSummaryProperty('in_progress_job_count');
    }
    
    
    /**
     * 
     * @param string $propertyName
     * @return mixed
     */
    private function getRemoteCoreApplicationSummaryProperty($propertyName) {
        $remoteCoreApplicationSummary = $this->getRemoteCoreApplicationSummary();
        return isset($remoteCoreApplicationSummary->$propertyName) ? $remoteCoreApplicationSummary->$propertyName : null;        
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    private function getRemoteCoreApplicationSummary() {
        if (is_null($this->remoteCoreApplicationSummary)) {
            $httpRequest = $this->getAuthorisedHttpRequest($this->getUrl());            

            try {
                $this->remoteCoreApplicationSummary = $this->webResourceService->get($httpRequest)->getContentObject();
            } catch (\webignition\Http\Client\CurlException $curlException) {
                var_dump("cp02", $curlException->getCode(), $curlException->getMessage());                
                return $curlException;
            } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
                var_dump("cp03", $webResourceServiceException->getCode(), $webResourceServiceException->getMessage());
                exit();                
                return $webResourceServiceException->getCode();
            }             
        }
        
        return $this->remoteCoreApplicationSummary;
    }
    
    
    
    
    //public function 
    
    
//    /**
//     *
//     * @param string $canonicalUrl
//     * @param int $testId
//     * @return boolean 
//     */
//    public function cancel($canonicalUrl, $testId) {
//        $httpRequest = $this->getAuthorisedHttpRequest(
//            $this->getUrl('test_cancel', array(
//            'canonical-url' => $canonicalUrl,
//            'test_id' => $testId
//        )));
//        
//        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
//        try {
//            $this->webResourceService->get($httpRequest);
//            return true;
//        } catch (\webignition\Http\Client\CurlException $curlException) {
//            $this->logger->warn('TestService::cancel:curlException: ['.$curlException->getCode().'] ['.$curlException->getMessage().']');
//            return $curlException;
//        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {                      
//            $this->logger->warn('TestService::cancel:WebResourceServiceException: ['.$webResourceServiceException->getCode().'] ['.$webResourceServiceException->getMessage().']');
////            if ($webResourceServiceException->getCode() == 403) {
////                return false;
////            }            
//            return $webResourceServiceException->getCode();
//        }        
//    }
    
    
}