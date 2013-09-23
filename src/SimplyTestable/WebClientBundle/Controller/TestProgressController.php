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
        'new' => 'New (waiting to start)',
        'queued' => 'Waiting for first test to begin',
        'preparing' => 'Finding URLs to test: looking for sitemap or news feed',
        'in-progress' => 'Running',
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
        
        if (in_array($test->getState(), $this->testFinishedStates)) {
            if ($test->getState() !== 'failed-no-sitemap') {
                return $this->redirect($this->getResultsUrl($website, $test_id));                
            }
            
            if ($this->getUserService()->isPublicUser($this->getUser())) {
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
        if ($test->getState() == 'failed-no-sitemap' && !isset($remoteTestSummary->crawl)) {
            $this->getTestService()->startCrawl($test);
            $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();                
        }        
                
        if ($test->getState() == 'failed-no-sitemap' && isset($remoteTestSummary->crawl)) {
            $test->setState('crawling');
        }
        
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeObject) {
            $taskTypes[] = $taskTypeObject->name;
        }
        
        $testOptions = $this->getTestOptionsFromRemoteTestSummary($remoteTestSummary);
        
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
            'task_types' => $taskTypes,
            'test_cancel_error' => $this->getFlash('test_cancel_error'),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions,
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
    
    private function getTestOptionsFromRemoteTestSummary($remoteTestSummary) {        
        $testOptions = array();
        $selectedTaskTypes = array();
        
        foreach ($remoteTestSummary->task_types as $taskType) {
            $taskTypeName = $taskType->name;
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskTypeName));
            
            $testOptions[$taskTypeKey] = 1;
            $selectedTaskTypes[] = $taskTypeKey;
        }
        
        foreach($remoteTestSummary->task_type_options as $taskTypeName => $taskTypeOptionsSet) {
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskTypeName));            
            
            foreach ($taskTypeOptionsSet as $taskTypeOptionKey => $taskTypeOptionValue) {
                $testOptions[$taskTypeKey.'-'.$taskTypeOptionKey] = $taskTypeOptionValue;
            }
        }
        
        $testOptionsParameters = $this->container->getParameter('test_options');                      
        foreach ($testOptionsParameters['names_and_default_values'] as $testOptionName => $defaultValue) {            
            if ($this->isTaskType($testOptionName)) {
                $testOptions[$testOptionName] = (int)isset($testOptions[$testOptionName]);
            } else {
                if (!in_array($this->getTaskTypeFromTaskTypeOption($testOptionName), $selectedTaskTypes)) {
                    $testOptions[$testOptionName] = $defaultValue;
                    
                    if ($this->isInvertableOption($testOptionName)) {
                        $testOptions[$testOptionName] = ($testOptions[$testOptionName]) ? 0 : 1;
                    }
                }

                if (!isset($testOptions[$testOptionName])) {
                    $testOptions[$testOptionName] = '';
                }                
            }
        }        
        
        return $this->invertInvertableOptions($testOptions);
    }
    
    private function getTaskTypeFromTaskTypeOption($taskTypeOption) {
        $availableTaskTypes = $this->container->getParameter('available_task_types'); 
        
        foreach ($availableTaskTypes['default'] as $taskTypeKey => $taskTypeName) {
            $keyLength = strlen($taskTypeKey);
            
            if (substr($taskTypeOption, 0, $keyLength) == $taskTypeKey) {
                return $taskTypeKey;
            }
        }
        
        return null;
    }    
    
    private function isTaskType($taskTypeOption) {
        $availableTaskTypes = $this->container->getParameter('available_task_types'); 
        
        foreach ($availableTaskTypes['default'] as $taskTypeKey => $taskTypeName) {
            if ($taskTypeKey == $taskTypeOption) {
                return true;
            }
        }
        
        return false;        
    } 
    
    private function invertInvertableOptions($testOptions) {
        $testOptionsParameters = $this->container->getParameter('test_options');        
        foreach ($testOptionsParameters['invert_option_keys'] as $optionName) {
            if (isset($testOptions[$optionName])) {   
                $testOptions[$optionName] = ($testOptions[$optionName] == '1') ? '0' : '1';
            } else {
                $testOptions[$optionName] = '1';
            }
        }
        
        return $testOptions;
    }   
    

    private function isInvertableOption($taskTypeOption) {
        $testOptionsParameters = $this->container->getParameter('test_options');        
        foreach ($testOptionsParameters['invert_option_keys'] as $optionName) {
            if ($optionName == $taskTypeOption) {
                return true;
            }
        }
        
        return false;        
    }    
    
    
    /**
     * 
     * @return array
     */
    private function getAvailableTaskTypes() {
        $allAvailableTaskTypes = $this->container->getParameter('available_task_types');
        $availableTaskTypes = $allAvailableTaskTypes['default'];
        
        if ($this->isEarlyAccessUser() && is_array($allAvailableTaskTypes['early_access'])) {
            $availableTaskTypes = array_merge($availableTaskTypes, $allAvailableTaskTypes['early_access']);
        }
        
        return $availableTaskTypes;
    }    
    
    private function getStateLabel($test, $remoteTestSummary) {
        if ($test->getState() == 'preparing' && isset($remoteTestSummary->crawl)) {
            return $this->testStateLabelMap['queued'];
        }
        
        $label = $this->testStateLabelMap[$test->getState()];
        
        if ($test->getState() == 'in-progress') {
            $label .= ': ' . $this->getCompletionPercent($remoteTestSummary).'% done';
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
}
