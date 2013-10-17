<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Entity\Test\Test;

class TaskCollectionFilterService extends TaskService {
    
    const OUTCOME_FILTER_SKIPPED = 'skipped';
    const OUTCOME_FILTER_CANCELLED = 'cancelled';
    
    
    /**
     *
     * @var Test
     */
    private $test;
    
    
    /**
     *
     * @var string
     */
    private $outcomeFilter = null;
    
    
    /**
     *
     * @var string
     */
    private $typeFilter = null;
    
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     */
    public function setTest(Test $test) {
        $this->test = $test;
    }
    
    
    /**
     * 
     * @param string $outcomeFilter
     */
    public function setOutcomeFilter($outcomeFilter) {
        $this->outcomeFilter = $outcomeFilter;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getOutcomeFilter() {
        return $this->outcomeFilter;
    }
    
    
    /**
     * 
     * @param string $typeFilter
     */
    public function setTypeFilter($typeFilter) {
        $this->typeFilter = $typeFilter;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getTypeFilter() {
        return $this->typeFilter;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getRemoteIds() {        
        if ($this->getOutcomeFilter() == self::OUTCOME_FILTER_SKIPPED || $this->getOutcomeFilter() == self::OUTCOME_FILTER_CANCELLED) {
            return $this->getEntityRepository()->getRemoteIdByTestAndTaskTypeIncludingStates(
                    $this->test,
                    $this->getTypeFilter(),
                    array($this->getOutcomeFilter())
            );
        }
        
        switch ($this->getOutcomeFilter()) {
            case 'without-errors':
                $issueCount = '= 0';
                $issueType = 'error';
                break;
            
            case 'with-errors':
                $issueCount = '> 0';
                $issueType = 'error';
                break;                
            
            case 'with-warnings':
                $issueCount = '> 0';
                $issueType = 'warning';
                break;             
        }
        
        return $this->getEntityRepository()->getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
                $this->test,
                $issueCount,
                $issueType,
                $this->getTypeFilter(),
                array('skipped', 'cancelled', 'in-progress', 'awaiting-cancellation')
        );      
    }  
    
}