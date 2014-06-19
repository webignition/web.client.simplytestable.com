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
