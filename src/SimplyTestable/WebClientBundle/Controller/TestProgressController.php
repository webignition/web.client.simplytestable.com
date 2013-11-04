<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest;

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
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {            
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();
        $isOwner = $this->getTestService()->getRemoteTestService()->owns();
        $isPublicUserTest = $test->getUser() == $this->getUserService()->getPublicUser()->getUsername();
        
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
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();        
         
        if ($test->getState() == 'failed-no-sitemap' && isset($remoteTest->crawl)) {
            $test->setState('crawling');
        }
        
        $taskTypes = $remoteTest->getTaskTypes();
        
        $this->getTestOptionsAdapter()->setRequestData($remoteTest->getOptions());
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();     

        $viewData = array(
            'website' => idn_to_utf8($website),
            'this_url' => $this->getProgressUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => $test_id
            )),
            'test' => $test,
            'remote_test' => $remoteTest,
            'state_label' => $this->getStateLabel($test, $remoteTest),
            'state_icon' => $this->testStateIconMap[$test->getState()],
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'is_owner' => $isOwner,
            'is_public_user_test' => $isPublicUserTest,
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
            $viewData['estimated_seconds_remaining'] = $this->getEstimatedSecondsRemaining($remoteTest);
            $viewData['remote_test'] = $remoteTest->__toArray();
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
    
    private function getStateLabel(Test $test, RemoteTest $remoteTest) {        
        if ($test->getState() == 'preparing') {
            return $this->testStateLabelMap['queued'];
        }
        
        $label = (isset($this->testStateLabelMap[$test->getState()])) ? $this->testStateLabelMap[$test->getState()] : '';
        
        if ($test->getState() == 'in-progress') {
            $label = $remoteTest->getCompletionPercent().'% done';
        }
        
        if ($test->getState() == 'crawling') {
            $label .= ': '. $remoteTest->getCrawl()->processed_url_count .' checked, ' . $remoteTest->getCrawl()->discovered_url_count.' of '. $remoteTest->getCrawl()->limit .' found';        }
        
        return $label;
    }  
        
//    private function getRemoteTestSummaryArray($remoteTestSummary) {        
//        $remoteTestSummaryArray = (array)$remoteTestSummary;
//        
//        foreach ($remoteTestSummaryArray as $key => $value) {            
//            if ($value instanceof \stdClass){
//                $remoteTestSummaryArray[$key] = get_object_vars($value);
//            }
//        }
//        
//        if (isset($remoteTestSummaryArray['task_type_options'])) {
//            foreach ($remoteTestSummaryArray['task_type_options'] as $testType => $testTypeOptions) {
//                $remoteTestSummaryArray['task_type_options'][$testType] = get_object_vars($testTypeOptions);
//            }
//        }
//        
//        if (isset($remoteTestSummaryArray['ammendments'])) {
//            $remoteTestSummaryArray['ammendments'] = array();
//            
//            foreach ($remoteTestSummary->ammendments as $ammendment) {
//                $ammendmentArray = (array)$ammendment;                
//                if (isset($ammendment->constraint)) {
//                    $ammendmentArray['constraint'] = (array)$ammendment->constraint;
//                }                
//                
//                $remoteTestSummaryArray['ammendments'][] = $ammendmentArray;
//            }
//        }
//        
//        return $remoteTestSummaryArray;
//    }
    
    
    /**
     * 
     * @param RemoteTest $remoteTestSummary
     * @return int
     */
    private function getEstimatedSecondsRemaining(RemoteTest $remoteTest) {
        /**
         * Estimated minutes remaining = remaining task count / (current tasks per minute / current active jobs)
         * Estimated seconds remaining = estimated minutes remaining * 60
         */
        
        $incompleteTaskStates = array(
            'queued',
            'in_progress',
            'queued-for-assignment'
        );
        
        $remainingTaskCount = 0;        
        $taskCountByState = $remoteTest->getTaskCountByState();
        
        foreach ($incompleteTaskStates as $incompleteTaskState) {            
            if (isset($taskCountByState[$incompleteTaskState])) {
                $remainingTaskCount += $taskCountByState[$incompleteTaskState];
            }            
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
    
    
//    /**
//     * 
//     * @param \stdClass $remoteTestSummary
//     * @return int
//     */
//    private function getCompletionPercentFromStdClass($remoteTestSummary) {
//        if ($remoteTestSummary->task_count === 0) {
//            return 0;
//        }
//        
//        $finishedCount = 0;
//        foreach ($remoteTestSummary->task_count_by_state as $stateName => $taskCount) {
//            if (in_array($stateName, $this->taskFinishedStates)) {
//                $finishedCount += $taskCount;
//            }
//        }
//        
//        if ($finishedCount ==  $remoteTestSummary->task_count) {
//            return 100;
//        }   
//        
//        $requiredPrecision = floor(log10($remoteTestSummary->task_count)) - 1;
//        
//        if ($requiredPrecision == 0) {
//            return floor(($finishedCount / $remoteTestSummary->task_count) * 100);
//        }        
//        
//        return round(($finishedCount / $remoteTestSummary->task_count) * 100, $requiredPrecision);        
//    }
    
    
//    /**
//     * 
//     * @param array $remoteTestSummary
//     * @return int
//     */
//    private function getCompletionPercentFromArray($remoteTestSummary) {
//        if ($remoteTestSummary['task_count'] === 0) {
//            return 0;
//        }
//        
//        $finishedCount = 0;
//        foreach ($remoteTestSummary['task_count_by_state'] as $stateName => $taskCount) {
//            if (in_array($stateName, $this->taskFinishedStates)) {
//                $finishedCount += $taskCount;
//            }
//        }
//        
//        if ($finishedCount ==  $remoteTestSummary['task_count']) {
//            return 100;
//        }   
//        
//        $requiredPrecision = floor(log10($remoteTestSummary['task_count'])) - 1;
//        
//        if ($requiredPrecision == 0) {
//            return floor(($finishedCount / $remoteTestSummary['task_count']) * 100);
//        }        
//        
//        return round(($finishedCount / $remoteTestSummary['task_count']) * 100, $requiredPrecision);        
//    }
//    
    

    
    
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
    
    
//    /**
//     * 
//     * @param \stdClass $remoteTestSummary
//     * @return \Symfony\Component\HttpFoundation\ParameterBag
//     */
//    private function remoteTestSummaryTestOptionsToParameterBag(\stdClass $remoteTestSummary) {
//        $parameterBag = new \Symfony\Component\HttpFoundation\ParameterBag();
//        
//        foreach ($remoteTestSummary->task_types as $taskType) {
//            $parameterBag->set(strtolower(str_replace(' ', '-', $taskType->name)), 1);
//        }
//        
//        foreach ($remoteTestSummary->task_type_options as $taskType => $taskTypeOptions) {
//            $taskTypeKey = strtolower(str_replace(' ', '-', $taskType));
//            
//            foreach ($taskTypeOptions as $taskTypeOptionKey => $taskTypeOptionValue) {
//                $parameterBag->set($taskTypeKey . '-' . $taskTypeOptionKey, $taskTypeOptionValue);
//            }
//        }
//        
//        return $parameterBag;
//    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }     
}
