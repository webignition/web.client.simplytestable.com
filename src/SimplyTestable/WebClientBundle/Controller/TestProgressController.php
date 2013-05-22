<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TestProgressController extends TestViewController
{        
    private $testFinishedStates = array(
        'cancelled',
        'completed',
        'failed-no-sitemap',
        'rejected'
    );
    
    private $taskFinishedStates = array(
        'cancelled',
        'completed',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached',
        'skipped'
    );    
    
    private $testStateLabelMap = array(
        'new' => 'New',
        'queued' => 'Queued',
        'preparing' => 'Discovering URLs to test',
        'in-progress' => 'Running'        
    );
    
    private $testStateIconMap = array(
        'new' => 'icon-off',
        'queued' => 'icon-off',
        'queued-for-assignment' => 'icon-off',
        'preparing' => 'icon-search',
        'in-progress' => 'icon-play-circle'        
    );
    
    public function indexAction($website, $test_id) {        
        $this->getTestService()->setUser($this->getUser());
        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();
        
        if (in_array($test->getState(), $this->testFinishedStates)) {
            return $this->redirect($this->getResultsUrl($website, $test_id));
        }
        
        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));            
        }        
        
        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();        
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeObject) {
            $taskTypes[] = $taskTypeObject->name;
        }        
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'this_url' => $this->getProgressUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => $test_id
            )),
            'test' => $test,
            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
            'state_label' => $this->testStateLabelMap[$test->getState()].': ',
            'state_icon' => $this->testStateIconMap[$test->getState()],
            'completion_percent' => $this->getCompletionPercent($remoteTestSummary),
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'task_types' => $taskTypes,
            'test_cancel_error' => $this->getFlash('test_cancel_error')
        );  
        
        if ($this->isJsonResponseRequired()) {
            $viewData['estimated_seconds_remaining'] = $this->getEstimatedSecondsRemaining($remoteTestSummary);
        }
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
    }    
        
    private function getRemoteTestSummaryArray($remoteTestSummary) {        
        $remoteTestSummaryArray = (array)$remoteTestSummary;
        
        foreach ($remoteTestSummaryArray as $key => $value) {            
            if ($value instanceof \stdClass){
                $remoteTestSummaryArray[$key] = get_object_vars($value);
            }
        }
        
        if (isset($remoteTestSummaryArray['task_type_options'])) {
            foreach ($remoteTestSummaryArray['task_type_options'] as $testType => $testTypeOptions) {
                $remoteTestSummaryArray['task_type_options'][$testType] = get_object_vars($testTypeOptions);
            }
        }
        
        if (isset($remoteTestSummaryArray['ammendments'])) {
            $remoteTestSummaryArray['ammendments'] = array();
            
            foreach ($remoteTestSummary->ammendments as $ammendment) {
                $ammendmentArray = (array)$ammendment;                
                if (isset($ammendment->constraint)) {
                    $ammendmentArray['constraint'] = (array)$ammendment->constraint;
                }                
                
                $remoteTestSummaryArray['ammendments'][] = $ammendmentArray;
            }
        }
        
        return $remoteTestSummaryArray;
    }
    
    
    /**
     * 
     * @param \stdClass $remoteTestSummary
     * @return int
     */
    private function getEstimatedSecondsRemaining(\stdClass $remoteTestSummary) {
        /**
         * Estimated minutes remaining = remaining task count / (current tasks per minute / current active jobs)
         * Estimated seconds remaining = estimated minutes remaining * 60
         */
        
        $incompleteTaskStates = array(
            'queued',
            'in-progress',
            'queued-for-assignment'
        );
        
        $remainingTaskCount = 0;
        foreach ($incompleteTaskStates as $incompleteTaskState) {
            $remainingTaskCount += $remoteTestSummary->task_count_by_state->$incompleteTaskState;
        }
        
        $tasksPerMinute = $this->getCoreApplicationStatusService()->getTaskThroughputPerMinute();        
        if ($tasksPerMinute === 0) {
            return -1;
        }
        
        $inProgressJobCount = $this->getCoreApplicationStatusService()->getInProgressJobCount();        
        if ($inProgressJobCount === 0) {
            return -1;
        }        

        $estimatedMinutesRemaining = $remainingTaskCount / ($tasksPerMinute / $inProgressJobCount);
        return (int)ceil($estimatedMinutesRemaining * 60);               
    }  
    
    
    /**
     *
     * @param \stdClass|array $remoteTestSummary
     * @return int 
     */
    private function getCompletionPercent($remoteTestSummary) {        
        return ($remoteTestSummary instanceof \stdClass) ? $this->getCompletionPercentFromStdClass($remoteTestSummary) : $this->getCompletionPercentFromArray($remoteTestSummary);
    } 
    
    
    /**
     * 
     * @param \stdClass $remoteTestSummary
     * @return int
     */
    private function getCompletionPercentFromStdClass($remoteTestSummary) {
        if ($remoteTestSummary->task_count === 0) {
            return 0;
        }
        
        $finishedCount = 0;
        foreach ($remoteTestSummary->task_count_by_state as $stateName => $taskCount) {
            if (in_array($stateName, $this->taskFinishedStates)) {
                $finishedCount += $taskCount;
            }
        }
        
        if ($finishedCount ==  $remoteTestSummary->task_count) {
            return 100;
        }   
        
        $requiredPrecision = floor(log10($remoteTestSummary->task_count)) - 1;
        
        if ($requiredPrecision == 0) {
            return floor(($finishedCount / $remoteTestSummary->task_count) * 100);
        }        
        
        return round(($finishedCount / $remoteTestSummary->task_count) * 100, $requiredPrecision);        
    }
    
    
    /**
     * 
     * @param array $remoteTestSummary
     * @return int
     */
    private function getCompletionPercentFromArray($remoteTestSummary) {
        if ($remoteTestSummary['task_count'] === 0) {
            return 0;
        }
        
        $finishedCount = 0;
        foreach ($remoteTestSummary['task_count_by_state'] as $stateName => $taskCount) {
            if (in_array($stateName, $this->taskFinishedStates)) {
                $finishedCount += $taskCount;
            }
        }
        
        if ($finishedCount ==  $remoteTestSummary['task_count']) {
            return 100;
        }   
        
        $requiredPrecision = floor(log10($remoteTestSummary['task_count'])) - 1;
        
        if ($requiredPrecision == 0) {
            return floor(($finishedCount / $remoteTestSummary['task_count']) * 100);
        }        
        
        return round(($finishedCount / $remoteTestSummary['task_count']) * 100, $requiredPrecision);        
    }
    
    
    /**
     *
     * @param \stdClass $remoteTestSummary
     * @return array 
     */
    private function getTaskCountByState(\stdClass $remoteTestSummary) {        
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
            
            if (isset($remoteTestSummary->task_count_by_state->$taskState)) {
                $taskCountByState[$translatedState] += $remoteTestSummary->task_count_by_state->$taskState;
            }            
        }
        
        return $taskCountByState;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\CoreApplicationStatusService
     */
    private function getCoreApplicationStatusService() {
        return $this->container->get('simplytestable.services.coreapplicationstatusservice');
    }
}
