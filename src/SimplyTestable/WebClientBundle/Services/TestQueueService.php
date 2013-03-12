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
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @param string $canonicalUrl
     * @return boolean
     */
    private function contains(User $user, $canonicalUrl) {
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