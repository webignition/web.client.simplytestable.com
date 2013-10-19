<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TestProgressController extends TestViewController
{       
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;    
    
    
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
        'new' => 'New, waiting to start',
        'queued' => 'Queued, waiting for first test to begin',
        'preparing' => 'Finding URLs to test: looking for sitemap or news feed',
        'crawling' => 'Finding URLs to test',
        'failed-no-sitemap' => 'Finding URLs to test: preparing to crawl'
    );
    
    private $testStateIconMap = array(
        'new' => 'icon-off',
        'queued' => 'icon-off',
        'queued-for-assignment' => 'icon-off',
        'preparing' => 'icon-search',
        'crawling' => 'icon-search',        
        'failed-no-sitemap' => 'icon-search',         
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
        $isOwner = $this->getTestService()->owns();
        
        if (in_array($test->getState(), $this->testFinishedStates)) {            
            if ($test->getState() !== 'failed-no-sitemap') {
                return $this->redirect($this->getResultsUrl($website, $test_id));                
            }
            
            if ($this->getUserService()->isPublicUser($this->getUser()) && $isOwner) {
                return $this->redirect($this->getResultsUrl($website, $test_id));
            }
        }       
        
        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));            
        }      
        
        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();        
         
        if ($test->getState() == 'failed-no-sitemap' && isset($remoteTestSummary->crawl)) {
            $test->setState('crawling');
        }
        
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeObject) {
            $taskTypes[] = $taskTypeObject->name;
        }
        
        $this->getTestOptionsAdapter()->setRequestData($this->remoteTestSummaryTestOptionsToParameterBag($remoteTestSummary));
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();     

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
            'state_label' => $this->getStateLabel($test, $remoteTestSummary),
            'state_icon' => $this->testStateIconMap[$test->getState()],
            'completion_percent' => $this->getCompletionPercent($remoteTestSummary),
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'is_owner' => $isOwner,
            'task_types' => $taskTypes,
            'test_cancel_error' => $this->getFlash('test_cancel_error'),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'default_css_validation_options' => array(
                'ignore-warnings' => 1,
                'vendor-extensions' => 'warn',
                'ignore-common-cdns' => 1                
            ),            
        );  
        
        if ($this->isJsonResponseRequired()) {
            $viewData['estimated_seconds_remaining'] = $this->getEstimatedSecondsRemaining($remoteTestSummary);
        }
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
    }
    
    /**
     * 
     * @return array
     */
    private function getCssValidationCommonCdnsToIgnore() {
        if (!$this->container->hasParameter('css-validation-ignore-common-cdns')) {
            return array();
        }
        
        return $this->container->getParameter('css-validation-ignore-common-cdns');
    } 
    
    
    /**
     * 
     * @return array
     */
    private function getAvailableTaskTypes() {
        $this->getAvailableTaskTypeService()->setUser($this->getUser());
        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());
        
        return $this->getAvailableTaskTypeService()->get();        
    }    
    
    private function getStateLabel($test, $remoteTestSummary) {
        if ($test->getState() == 'preparing' && isset($remoteTestSummary->crawl)) {
            return $this->testStateLabelMap['queued'];
        }
        
        $label = (isset($this->testStateLabelMap[$test->getState()])) ? $this->testStateLabelMap[$test->getState()] : '';
        
        if ($test->getState() == 'in-progress') {
            $label = $this->getCompletionPercent($remoteTestSummary).'% done';
        }
        
        if ($test->getState() == 'crawling') {
            $label .= ': '. $remoteTestSummary->crawl->processed_url_count .' checked, ' . $remoteTestSummary->crawl->discovered_url_count.' of '. $remoteTestSummary->crawl->limit .' found';
        }
        
        return $label;
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
        if ($remoteTestSummary->state === 'failed-no-sitemap' && isset($remoteTestSummary->crawl)) {
            if ($remoteTestSummary->crawl->discovered_url_count === 0) {
                return 0;
            }
            
            return round(($remoteTestSummary->crawl->discovered_url_count / $remoteTestSummary->crawl->limit) * 100);
        }
        
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
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');        
            
            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');
        
            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getAvailableTaskTypes());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
            $this->testOptionsAdapter->setInvertInvertableOptions(true);
        }
        
        return $this->testOptionsAdapter;
    }
    
    
    /**
     * 
     * @param \stdClass $remoteTestSummary
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function remoteTestSummaryTestOptionsToParameterBag(\stdClass $remoteTestSummary) {
        $parameterBag = new \Symfony\Component\HttpFoundation\ParameterBag();
        
        foreach ($remoteTestSummary->task_types as $taskType) {
            $parameterBag->set(strtolower(str_replace(' ', '-', $taskType->name)), 1);
        }
        
        foreach ($remoteTestSummary->task_type_options as $taskType => $taskTypeOptions) {
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskType));
            
            foreach ($taskTypeOptions as $taskTypeOptionKey => $taskTypeOptionValue) {
                $parameterBag->set($taskTypeKey . '-' . $taskTypeOptionKey, $taskTypeOptionValue);
            }
        }
        
        return $parameterBag;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }     
}
