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
        
        if (is_null($this->remoteCoreApplicationSummary)) {
            return null;
        }
        
        return isset($remoteCoreApplicationSummary->$propertyName) ? $remoteCoreApplicationSummary->$propertyName : null;        
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    private function getRemoteCoreApplicationSummary() {
        if (is_null($this->remoteCoreApplicationSummary)) {
            $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl());
            $this->addAuthorisationToRequest($httpRequest);          

            try {
                $this->remoteCoreApplicationSummary = $this->webResourceService->get($httpRequest)->getContentObject();
            } catch (\Exception $curlException) {
                return null;
            }             
        }
        
        return $this->remoteCoreApplicationSummary;
    }
    
    
}