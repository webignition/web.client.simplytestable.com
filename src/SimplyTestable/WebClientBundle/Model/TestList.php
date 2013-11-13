<?php
namespace SimplyTestable\WebClientBundle\Model;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class TestList  {
    
    const PAGINATION_PAGE_COLLECTION_SIZE = 10;
    
    private $maxResults = 0;
    private $offset = 0;
    private $limit = 1;
    
    private $tests = array();
    
    public function setMaxResults($maxResults) {
        $this->maxResults = $maxResults;
    }
    
    public function getMaxResults() {
        return $this->maxResults;
    }
    
    public function setOffset($offset) {
        $this->offset = $offset;
    }
    
    public function getOffset() {
        return $this->offset;
    }
    
    public function setLimit($limit) {
        $this->limit = $limit;
    }
    
    public function getLimit() {
        return $this->limit;
    }
    
    public function addRemoteTest(RemoteTest $remoteTest) {
        if (!isset($this->tests[$remoteTest->getId()])) {
            $this->tests[$remoteTest->getId()] = array();
        }
        
        $this->tests[$remoteTest->getId()]['remote_test'] = $remoteTest;
    }        
    
    public function addTest(Test $test) {
        if (!isset($this->tests[$test->getTestId()])) {
            $this->tests[$test->getTestId()] = array();
        } 
        
        $this->tests[$test->getTestId()]['test'] = $test;
    }
    
    public function get() {
        return $this->tests;
    }
    
    /**
     * 
     * @return int
     */
    public function getLength() {
        return count($this->tests);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isEmpty() {
        return $this->getLength() === 0;
    }
    
    
    public function getPageNumber() {        
        return $this->getPageIndex() + 1;
    }
    
    
    public function getPageCount() {
        return (int)ceil($this->getMaxResults() / $this->getLimit());
    }
    
    public function getPageIndex() {        
        return $this->getOffset() / $this->getLimit();
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return boolean|null
     */
    public function requiresResults(Test $test) {
        if (!$this->containsLocal($test->getTestId()) || !$this->containsRemote($test->getTestId())) {
            return null;
        }
        
        return $this->tests[$test->getTestId()]['remote_test']->getTaskCount() != $test->getTaskCount();
    }
    

    /**
     * 
     * @param int $testId
     * @return boolean
     */    
    private function containsRemote($testId) {
        if (!isset($this->tests[$testId])) {
            return false;
        }
        
        return isset($this->tests[$testId]['remote_test']);
    }
    

    /**
     * 
     * @param int $testId
     * @return boolean
     */
    private function containsLocal($testId) {
        if (!isset($this->tests[$testId])) {
            return false;
        }
        
        return isset($this->tests[$testId]['test']);
    }
    
    
    /**
     * 
     * @return int
     */
    public function getPageCollectionNumber() {
        return $this->getPageIndex() + 1;
    }
    
    public function getPageCollectionIndex() {
        return (int)floor($this->getPageIndex() / self::PAGINATION_PAGE_COLLECTION_SIZE);
    }    
    
    public function getPageNumbers() {               
        if ($this->getMaxResults() <= $this->getLimit()) {            
            return array();
        }
        
        
        $start = $this->getPageCollectionIndex() * $this->getLimit();
        $end = $start + $this->getLimit() - 1;
        
        $pageNumbers = array();
        
        for ($pageIndex = $start; $pageIndex <= $end; $pageIndex++) {                        
            if ($this->isValidPageIndex($pageIndex)) {                
                $pageNumbers[] = $pageIndex + 1;
            }            
        }
      
        return $pageNumbers;
    }
    
    private function isValidPageIndex($index) {
        return $this->getMaxResults() > ($index) * $this->getLimit();
    }
    
    /**
     * 
     * @return string
     */
    public function getHash() {
        return md5($this->getHashableContent());
    }
    
    
    /**
     * @return string
     */
    private function getHashableContent() {        
        $hashableContent = json_encode($this->getPropertiesString());
        
        foreach ($this->get() as $testId => $testData) {
            $testDataHashableContent = array();
            
            if ($this->containsLocal($testId)) {
                $testDataHashableContent['requires_results'] = $this->requiresResults($testData['test']);
            }
            
            if ($this->containsRemote($testId) && $this->requiresResults($testData['test'])) {
                $testDataHashableContent['remote_test'] = $testData['remote_test']->getSource();
            }
            
            $hashableContent .= json_encode($testDataHashableContent);
        }
        
        return $hashableContent;        
    }
    
    private function getPropertiesString() {
        return json_encode(array(
            'max_results' => $this->getMaxResults(),
            'offset' => $this->getOffset(),
            'limit' => $this->getLimit(),
            'page_index' => $this->getPageIndex(),
            'page_collection_index' => $this->getPageCollectionIndex(),
            'length' => $this->getLength()
        ));
    }
    
}