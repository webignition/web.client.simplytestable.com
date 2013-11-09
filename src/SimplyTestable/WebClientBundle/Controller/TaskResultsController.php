<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TaskResultsController extends TaskController
{  
    
    public function retrieveAction($website, $test_id)
    {
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->sendNotFoundResponse();
        } 
        
        try {
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());            
        } catch (UserServiceException $e) {
            return $this->sendNotFoundResponse();            
        }
        
        $this->getTaskService()->getCollection($test, $this->getRequestRemoteTaskIds());
        
        return new Response();
    }
    
    
    public function statusAction($website, $test_id)
    {
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->sendNotFoundResponse();
        } 
        
        try {
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());            
        } catch (UserServiceException $e) {
            return $this->sendNotFoundResponse();            
        }
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();
        
        $localTaskCount = $test->getTaskCount();
        $completionPercent = round(($localTaskCount / $remoteTest->getTaskCount()) * 100, $this->getCompletionPercentPrecision($remoteTest->getTaskCount()));
        $remainingTasksToRetrieveCount = $remoteTest->getTaskCount() - $localTaskCount;
        
        return new Response($this->getSerializer()->serialize(array(
            'completion-percent' => $completionPercent,
            'remaining-tasks-to-retrieve-count' => $remainingTasksToRetrieveCount,
            'local-task-count' => $localTaskCount,
            'remote-task-count' => $remoteTest->getTaskCount()            
        ), 'json'));
    }
    
    
    /**
     * 
     * @param int $remoteTaskCount
     * @return int
     */
    private function getCompletionPercentPrecision($remoteTaskCount) {        
        if ($remoteTaskCount <= 10000) {
            return 0;
        }
        
        return 2;
        
        return (int)floor(log10($remoteTaskCount)) - 1;        
    }
    
    
    /**
     *
     * @return array|null
     */
    private function getRequestRemoteTaskIds() {        
        $requestTaskIds = $this->getRequestValue('remoteTaskIds');        
        $taskIds = array();
        
        if (substr_count($requestTaskIds, ':')) {
            $rangeLimits = explode(':', $requestTaskIds);            
            $start = (int)$rangeLimits[0];
            $end = (int)$rangeLimits[1];
            
            for ($i = $start; $i <= $end; $i++) {
                $taskIds[] = $i;
            }
        } else {
            $rawRequestTaskIds = explode(',', $requestTaskIds);

            foreach ($rawRequestTaskIds as $requestTaskId) {
                if (ctype_digit($requestTaskId)) {
                    $taskIds[] = (int)$requestTaskId;
                }
            }            
        }
        
        return (count($taskIds) > 0) ? $taskIds : null;
    }      

}
