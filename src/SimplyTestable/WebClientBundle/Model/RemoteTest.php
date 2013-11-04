<?php
namespace SimplyTestable\WebClientBundle\Model;

use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpFoundation\ParameterBag;

class RemoteTest {
    
    
    /**
     *
     * @var array
     */
    private $taskFinishedStates = array(
        'cancelled',
        'completed',
        'failed',
        'skipped'
    );      
    
    
    /**
     *
     * @var \stdClass
     */
    private $rawRemoteTest;
    
    
    /**
     * 
     * @param \stdClass $rawRemoteTest
     */
    public function __construct(\stdClass $rawRemoteTest) {
        $this->rawRemoteTest = $rawRemoteTest;
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    public function getRaw() {
        return $this->rawRemoteTest;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getState() {
        $state = $this->getRemoteTestProperty('state');
        
        if ($state == 'failed-no-sitemap' && $this->hasCrawl()) {
            return 'crawling';
        }
        
        return $state;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getType() {
        return $this->getRemoteTestProperty('type');
    }
    
    /**
     * 
     * @return int|null
     */
    public function getUrlCount() {
        return $this->getRemoteTestProperty('url_count');
    }
    
    
    
    /**
     * 
     * @return int|null
     */
    public function getTaskCount() {
        return $this->getRemoteTestProperty('task_count');      
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Entity\TimePeriod|null
     */
    public function getTimePeriod() {
        if (!$this->hasRemoteTestProperty('time_period')) {
            return null;
        }
        
        $remoteTimePeriod = $this->getRemoteTestProperty('time_period');
        
        $timePeriod = new TimePeriod();
        
        if (isset($remoteTimePeriod->start_date_time)) {
            $timePeriod->setStartDateTime(new \DateTime($remoteTimePeriod->start_date_time));
        }
        
        if (isset($remoteTimePeriod->end_date_time)) {
            $timePeriod->setStartDateTime(new \DateTime($remoteTimePeriod->end_date_time));
        } 
        
        return $timePeriod;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getTaskTypes() {
        $taskTypes = array();
        foreach ($this->getRaw()->task_types as $taskTypeObject) {
            $taskTypes[] = $taskTypeObject->name;
        }        
        
        return $taskTypes;
    }
    
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    public function getOptions() {
        $parameterBag = new \Symfony\Component\HttpFoundation\ParameterBag();
        
        foreach ($this->getTaskTypes() as $taskType) {
            $parameterBag->set(strtolower(str_replace(' ', '-', $taskType)), 1);
        }
        
        foreach ($this->getRaw()->task_type_options as $taskType => $taskTypeOptions) {
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskType));
            
            foreach ($taskTypeOptions as $taskTypeOptionKey => $taskTypeOptionValue) {
                $parameterBag->set($taskTypeKey . '-' . $taskTypeOptionKey, $taskTypeOptionValue);
            }
        }
        
        return $parameterBag;        
    } 
    
    
    /**
     *
     * @return array 
     */
    public function getTaskCountByState() {        
        $taskStates = array(
            'in-progress' => 'in_progress',
            'queued' => 'queued',
            'queued-for-assignment' => 'queued',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'awaiting-cancellation' => 'cancelled',
            'failed' => 'failed',
            'failed-no-retry-available' => 'failed',
            'failed-retry-available' => 'failed',
            'failed-retry-limit-reached' => 'failed',
            'skipped' => 'skipped'
        );
        
        $taskCountByState = array();        
        
        foreach ($taskStates as $taskState => $translatedState) {
            if (!isset($taskCountByState[$translatedState])) {
                $taskCountByState[$translatedState] = 0;
            }
            
            if (isset($this->getRaw()->task_count_by_state->$taskState)) {
                $taskCountByState[$translatedState] += $this->getRaw()->task_count_by_state->$taskState;
            }            
        }
        
        return $taskCountByState;
    } 
    
    
    
    /**
     * 
     * @return \stdClass|null
     */
    public function getCrawl() {
        return $this->getRemoteTestProperty('crawl');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasCrawl() {
        return !is_null($this->getCrawl());
    }
    
    
    /**
     *
     * @return int 
     */
    public function getCompletionPercent() {
        if ($this->getState() === 'crawling' && $this->hasCrawl()) {            
            $crawl = $this->getCrawl();            
            if ($crawl->discovered_url_count === 0) {
                return 0;
            }
            
            return round(($crawl->discovered_url_count / $crawl->limit) * 100);
        }
        
        if ($this->getTaskCount() === 0) {
            return 0;
        }
        
        $finishedCount = 0;
        foreach ($this->getTaskCountByState() as $stateName => $taskCount) {
            if (in_array($stateName, $this->taskFinishedStates)) {
                $finishedCount += $taskCount;
            }
        }
        
        if ($finishedCount == $this->getTaskCount()) {
            return 100;
        }   
        
        $requiredPrecision = floor(log10($this->getTaskCount())) - 1;
        
        if ($requiredPrecision == 0) {
            return floor(($finishedCount / $this->getTaskCount()) * 100);
        }        
        
        return round(($finishedCount / $this->getTaskCount()) * 100, $requiredPrecision);         
    }    
    
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    private function getRemoteTestProperty($name) {
        if (!$this->hasRemoteTestProperty($name)) {
            return null;
        }
        
        return $this->rawRemoteTest->$name;
    }
    
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    private function hasRemoteTestProperty($name) {
        return isset($this->rawRemoteTest->$name);
    }
    
    
    
    /**
     * 
     * @return array
     */
    public function __toArray() {        
        $remoteTestArray = (array)$this->getRaw();
        
        foreach ($remoteTestArray as $key => $value) {            
            if ($value instanceof \stdClass){
                $remoteTestArray[$key] = get_object_vars($value);
            }
        }
        
        $remoteTestArray['task_count_by_state'] = $this->getTaskCountByState();
        $remoteTestArray['completion_percent'] = $this->getCompletionPercent();
        
        if (isset($remoteTestArray['task_type_options'])) {
            foreach ($remoteTestArray['task_type_options'] as $testType => $testTypeOptions) {
                $remoteTestArray['task_type_options'][$testType] = get_object_vars($testTypeOptions);
            }
        }
        
        if (isset($remoteTestArray['ammendments'])) {
            $remoteTestArray['ammendments'] = array();
            
            foreach ($this->getRaw()->ammendments as $ammendment) {
                $ammendmentArray = (array)$ammendment;                
                if (isset($ammendment->constraint)) {
                    $ammendmentArray['constraint'] = (array)$ammendment->constraint;
                }                
                
                $remoteTestArray['ammendments'][] = $ammendmentArray;
            }
        }
        
        return $remoteTestArray;
    } 
    
    
    /**
     * 
     * @return string
     */
    public function getUser() {
        return $this->getRemoteTestProperty('user');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsPublic() {        
        return $this->getRemoteTestProperty('is_public');
    }
    
    
    
    /**
     * 
     * @return int
     */
    public function getErroredTaskCount() {
        return $this->getRemoteTestProperty('errored_task_count');
    }
    
    
    /**
     * 
     * @return int
     */
    public function getCancelledTaskCount() {
        return $this->getRemoteTestProperty('cancelled_task_count');
    }
    

    /**
     * 
     * @return int
     */
    public function getSkippedTaskCount() {
        return $this->getRemoteTestProperty('skipped_task_count');
    }
    
    
    /**
     * 
     * @return int
     */
    public function getWarningedTaskCount() {
        return $this->getRemoteTestProperty('warninged_task_count');
    }
    
    
    /**
     * 
     * @return int
     */
    public function getErrorFreeTaskCount() {        
        return $this->getTaskCount() - $this->getErroredTaskCount() - $this->getCancelledTaskCount() - $this->getWarningedTaskCount();
    }
    
    
    /**
     * 
     * @return string
     */
    public function getWebsite() {
        return $this->getRemoteTestProperty('website');
    }
    
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->getRemoteTestProperty('id');
    }
    
    
    /**
     * 
     * @return array
     */
    public function getAmmendments() {
        return $this->getRemoteTestProperty('ammendments');
    }
    
}