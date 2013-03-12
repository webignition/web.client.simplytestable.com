<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\TestOptions;

class TestQueueService {
    
    
    const QUEUE_RELATIVE_PATH = '/../data/queue/test';
    
    /**
     *
     * @var string
     */
    private $applicationRootDirectory;
    
    /**
     * 
     * @param string $applicationRootDirectory
     */
    public function setApplicationRootDirectory($applicationRootDirectory) {
        $this->applicationRootDirectory = $applicationRootDirectory;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getApplicationRootDirectory() {
        return $this->applicationRootDirectory;
    }  
    
    
    public function getRemoteTestSummary(User $user, $canonicalUrl) {  
        $test = $this->retrieve($user, $canonicalUrl);        
        
        $taskTypes = array();
        
        foreach ($test['options']->getTestTypes() as $taskType) {
            $taskTypeObject = new \stdClass();
            $taskTypeObject->name = $taskType;
            
            $taskTypes[] = $taskTypeObject;
        }
        
        $taskCountByState = new \stdClass();
        $taskCountByState->cancelled = 0;
        $taskCountByState->queued = 0;
        $taskCountByState->{'in-progress'} = 0;
        $taskCountByState->completed = 0;
        $taskCountByState->{'awaiting-cancellation'} = 0;
        $taskCountByState->{'queued-for-assignment'} = 0;
        $taskCountByState->{'failed-no-retry-available'} = 0;
        $taskCountByState->{'failed-retry-available'} = 0;
        $taskCountByState->{'failed-retry-limit-reached'} = 0;
        $taskCountByState->skipped = 0;        
        
        $summary = new \stdClass();
        $summary->website = $canonicalUrl;
        $summary->state = 'new-queued';
        $summary->url_count = 0;
        $summary->task_count = 0;
        $summary->task_count_by_state = $taskCountByState;
        $summary->task_types = $taskTypes;
        
        $summary->errored_task_count = 0;
        $summary->cancelled_task_count = 0;
        $summary->skipped_task_count = 0;
        $summary->task_type_options = array();
        $summary->type = $test['type'];
        
        return $summary;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @param string $canonicalUrl
     * @param \SimplyTestable\WebClientBundle\Model\TestOptions $testOptions
     * @param string $testType
     * @return boolean
     */
    public function enqueue(User $user, $canonicalUrl, TestOptions $testOptions, $testType = 'full site') {        
        if ($this->contains($user, $canonicalUrl)) {
            return true;
        }
        
        $testBasePath = $this->getTestBasePath($user, $canonicalUrl);
        
        $this->makeDirectory($testBasePath);
        
        file_put_contents($testBasePath . '/options', serialize($testOptions));
        file_put_contents($testBasePath . '/type', $testType);
        
        return true;
    }
    
    
    public function retrieve(User $user, $canonicalUrl) {        
        if (!$this->contains($user, $canonicalUrl)) {
            return false;
        }
        
        $testBasePath = $this->getTestBasePath($user, $canonicalUrl);
        
        return array(
            'options' => unserialize(file_get_contents($testBasePath . '/options')),
            'type' => file_get_contents($testBasePath . '/type')
        );
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @param string $canonicalUrl
     * @return boolean
     */
    public function contains(User $user, $canonicalUrl) {         
        return file_exists($this->getTestBasePath($user, $canonicalUrl));
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @param string $canonicalUrl
     * @return string
     */
    private function getTestBasePath(User $user, $canonicalUrl) {
        return $this->getQueueBasePath() . '/' . md5($user->getUsername()) . '/' . md5($canonicalUrl);
    }
    
    
    /**
     * 
     * @return string
     */
    private function getQueueBasePath() {
        return realpath($this->getApplicationRootDirectory() . self::QUEUE_RELATIVE_PATH);
    }
    
    
    /**
     * 
     * @param string $path
     * @return boolean
     */
    private function makeDirectory($path) {
        if (!file_exists($path)) {            
            return mkdir($path, 0777, true);
        }
        
        return is_file($path);                    
    }   
    
    
    
    
}