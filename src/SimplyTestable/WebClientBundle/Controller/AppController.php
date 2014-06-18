<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class AppController extends TestViewController
{   
    const RESULTS_PREPARATION_THRESHOLD = 10;
    
    
    private $testFinishedStates = array(
        'cancelled',
        'completed',
        'failed-no-sitemap',
    );



    
    
    public function prepareResultsStatsAction($website, $test_id)
    {        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());        
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }
        
        $test = $testRetrievalOutcome->getTest();

        if (!in_array($test->getState(), $this->testFinishedStates)) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }        
        
        if (!$test->hasTaskIds()) {
            $this->getTaskService()->getRemoteTaskIds($test);
        }  
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();
        
        $localTaskCount = $test->getTaskCount();      
        $completionPercent = round(($localTaskCount / $remoteTest->getTaskCount()) * 100);
        $remainingTasksToRetrieveCount = $remoteTest->getTaskCount() - $localTaskCount;        
        
        return $this->getUncacheableResponse(new Response($this->getSerializer()->serialize(array(
            'id' => $test->getTestId(),
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remainingTasksToRetrieveCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTest->getTaskCount()            
        ), 'json')));
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService 
     */
    private function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
    }


}
