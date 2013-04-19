<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class AppController extends BaseViewController
{    
    const RESULTS_PAGE_LENGTH = 100;
    const RESULTS_PREPARATION_THRESHOLD = 100;
    
    private $testFinishedStates = array(
        'cancelled',
        'completed',
        'no-sitemap',
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
    
    private $testOptionNamesAndDefaultValues = array(
        'html-validation' => 1,
        'css-validation' => 1,
        'css-validation-ignore-warnings' => 1,
        'css-validation-ignore-common-cdns' => 1,
        'css-validation-vendor-extensions' => "warn",
        'css-validation-domains-to-ignore' => "",
        'js-static-analysis' => 1,
        'js-static-analysis-ignore-common-cdns' => 1,
        'js-static-analysis-domains-to-ignore' => "",
    ); 
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private $testQueueService;
    
    
    public function indexAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $templateName = 'SimplyTestableWebClientBundle:App:index.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);
        
        $testOptions = $this->getPersistentValues($this->testOptionNamesAndDefaultValues);
        
        $testStartError = $this->getFlash('test_start_error');        
        
        $recentTests = $this->getRecentTests(9);
        $recentTestsHash = md5(json_encode($recentTests));        
        
        $testCancelledQueuedWebsite = $this->getFlash('test_cancelled_queued_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'test_start_error' => $testStartError,
            'recent_tests_hash' => $recentTestsHash,
            'test_cancelled_queued_website' => $testCancelledQueuedWebsite
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
            if ($response->isNotModified($this->getRequest())) {
                return $response;
            }
        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);
        
        return $this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $testStartError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'recent_tests' => $recentTests,
            'website' => idn_to_utf8($this->getPersistentValue('website')),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions,
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'test_options_title' => 'What do you want to check?',
            'test_cancelled_queued_website' => $testCancelledQueuedWebsite
        ));         

        return $this->getCachableResponse($this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $hasTestStartError,
            'test_start_error_blocked_website' => $hasTestStartBlockedWebsiteError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'recent_tests' => $recentTests
        )), $cacheValidatorHeaders);        
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
        $allAvailableTaskTypes = $this->container->getParameter('available_task_types');
        $availableTaskTypes = $allAvailableTaskTypes['default'];
        
        if ($this->isEarlyAccessUser()) {
            $availableTaskTypes = array_merge($availableTaskTypes, $allAvailableTaskTypes['early_access']);
        }
        
        return $availableTaskTypes;
    }
    
    
    /**
     * 
     * @return array
     */
    private function getRecentTests($limit = 3) {
        if (!$this->isLoggedIn()) {
            return array();
        }
        
        $jsonResource = $this->getTestService()->getList($limit);
        
        if (!$jsonResource instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
            return array();
        }
        
        $recentTests = json_decode($jsonResource->getContent(), true);
        
        foreach ($recentTests as $testIndex => $test) {            
            $recentTests[$testIndex]['website_label'] = $this->getWebsiteLabel($recentTests[$testIndex]['website']);
            $recentTests[$testIndex]['state_icon'] = $this->getTestStateIcon($recentTests[$testIndex]['state']);
            $recentTests[$testIndex]['state_label_class'] = $this->getTestStateLabelClass($recentTests[$testIndex]['state']);
            $recentTests[$testIndex]['completion_percent'] = $this->getCompletionPercent($test);
        }
        
        return $recentTests;
    }
    
    
    /**
     * Get presentation label for a website - canonical url minus scheme minus trailing slash
     * 
     * @param string $website
     * @return string
     */
    private function getWebsiteLabel($website) {
        $websiteUrl = new \webignition\Url\Url($website);
        
        $websiteLabel = preg_replace('/^'.$websiteUrl->getScheme().':\/\//', '', $website);
        $websiteLabel = preg_replace('/\/$/', '', $websiteLabel);
        
        return $websiteLabel;
    }
    
    
    /**
     * 
     * @param string $state
     * @return string
     */
    private function getTestStateIcon($state) {
        switch ($state) {
            case 'new':
                return 'icon-off';
            
            case 'queued':
                return 'icon-off';
            
            case 'preparing':
                return 'icon-cog';
            
            case 'in-progress':
                return 'icon-play-circle';
            
            case 'completed':
                return 'icon-bar-chart';
            
            case 'cancelled':
                return 'icon-bar-chart';
        }       
    }
    
     /**
     * 
     * @param string $state
     * @return string
     */
    private function getTestStateLabelClass($state) {        
        switch ($state) {
            case 'new':
                return '';
            
            case 'queued':
                return 'info';
            
            case 'preparing':
                return 'warning';
            
            case 'in-progress':
                return 'warning';
            
            case 'completed':
                return 'success';
            
            case 'cancelled':
                return 'success';
        }        
    }
    
    
    public function queuedAction($website) {
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        } 
        
        $normalisedWebsite = new \webignition\NormalisedUrl\NormalisedUrl($website);
        
        if (!$this->getTestQueueService()->contains($this->getUser(), (string)$normalisedWebsite)) {
            return $this->redirect($this->generateUrl('app_website', array(
                'website' => (string)$normalisedWebsite,
            ), true));            
        }
        
        $queuedTest = $this->getTestQueueService()->retrieve($this->getUser(), (string)$normalisedWebsite);
        
        $remoteTestSummary = $this->getTestQueueService()->getRemoteTestSummary($this->getUser(), (string)$normalisedWebsite);
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeObject) {
            $taskTypes[] = $taskTypeObject->name;
        }
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'this_url' => $this->getQueuedUrl($website),
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => null
            )),
            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
            'state_label' => $this->testStateLabelMap['queued'].': ',
            'state_icon' => $this->testStateIconMap['queued'],
            'completion_percent' => 0,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'task_types' => $taskTypes,
            'reason' => $queuedTest['reason']
        ); 
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
    }
    
    
    /**
     * 
     * @param string $website
     * @param int $test_id
     * @return \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome
     */
    private function getTestRetrievalOutcome($website, $test_id) {
        $outcome = new \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome();
        
        try {
            if ($this->getTestService()->has($website, $test_id, $this->getUser())) {
                $outcome->setTest($this->getTestService()->get($website, $test_id, $this->getUser()));
            } else {
                $outcome->setResponse( $this->redirect($this->generateUrl('app_test_redirector', array(
                    'website' => $website,
                    'test_id' => $test_id
                ), true)));               
            }            
        } catch (UserServiceException $e) {
            if ($this->isLoggedIn()) {
                $this->setTemplate('SimplyTestableWebClientBundle:App:test-not-authorised.html.twig');
                $outcome->setResponse($this->sendResponse(array(
                    'this_url' => $this->getProgressUrl($website, $test_id),
                    'test_input_action_url' => $this->generateUrl('test_cancel', array(
                        'website' => $website,
                        'test_id' => $test_id
                    )),
                    'website' => $website,
                    'test_id' => $test_id,
                    'public_site' => $this->container->getParameter('public_site'),
                    'user' => $this->getUser(),
                    'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                
                )));                
            } else {
                $redirectParameters = json_encode(array(
                    'route' => 'app_progress',
                    'parameters' => array(
                    'website' => $website,
                    'test_id' => $test_id                        
                    )
                ));
                
                $this->get('session')->setFlash('user_signin_error', 'test-not-logged-in');
                
                $outcome->setResponse($this->redirect($this->generateUrl('sign_in', array(
                    'redirect' => base64_encode($redirectParameters)
                ), true)));               
            }          
        }
        
        return $outcome;
    }
    
    public function progressAction($website, $test_id) {
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
    
    public function prepareResultsAction($website, $test_id)
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        } 
        
        try {            
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());            
        } catch (UserServiceException $e) {
            if (!$this->isLoggedIn()) {                
                $redirectParameters = json_encode(array(
                    'route' => 'app_progress',
                    'parameters' => array(
                    'website' => $website,
                    'test_id' => $test_id                        
                    )
                ));
                
                $this->get('session')->setFlash('user_signin_error', 'test-not-logged-in');
                return $this->redirect($this->generateUrl('sign_in', array(
                    'redirect' => base64_encode($redirectParameters)
                ), true));                
            }
            
            $this->setTemplate('SimplyTestableWebClientBundle:App:test-not-authorised.html.twig');
            return $this->sendResponse(array(
                'this_url' => $this->getProgressUrl($website, $test_id),
                'test_input_action_url' => $this->generateUrl('test_cancel', array(
                    'website' => $website,
                    'test_id' => $test_id
                )),
                'website' => $website,
                'test_id' => $test_id,
                'public_site' => $this->container->getParameter('public_site'),
                'user' => $this->getUser(),
                'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                
            ));            
        } 
        
        if (!$test->hasTaskIds()) {
            $this->getTaskService()->getRemoteTaskIds($test);
        }
        
        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();
        
        $localTaskCount = $test->getTaskCount();
        $remoteTaskCount = $remoteTestSummary->task_count;        
        $completionPercent = round(($localTaskCount / $remoteTaskCount) * 100);
        $remainingTasksToRetrieveCount = $remoteTaskCount - $localTaskCount;
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:results-preparing.html.twig');
        return $this->sendResponse(array(            
            'public_site' => $this->container->getParameter('public_site'),
            'this_url' => $this->getPreparingResultsUrl($website, $test_id),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'website' => idn_to_utf8($website),
            'test' => $test,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remainingTasksToRetrieveCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTaskCount
        ));     
    }
    
    
    
    public function resultsAction($website, $test_id) {                                
        $this->getTestService()->setUser($this->getUser());
        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }
        
        $taskListFilter = $this->getRequestValue('filter', 'with-errors');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        $cacheValidatorIdentifier->setParameter('filter', $taskListFilter);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        
        try {            
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());            
        } catch (UserServiceException $e) {
            if (!$this->isLoggedIn()) {                
                $redirectParameters = json_encode(array(
                    'route' => 'app_progress',
                    'parameters' => array(
                    'website' => $website,
                    'test_id' => $test_id                        
                    )
                ));
                
                $this->get('session')->setFlash('user_signin_error', 'test-not-logged-in');
                return $this->redirect($this->generateUrl('sign_in', array(
                    'redirect' => base64_encode($redirectParameters)
                ), true));                
            }
            
            $this->setTemplate('SimplyTestableWebClientBundle:App:test-not-authorised.html.twig');
            return $this->sendResponse(array(
                'this_url' => $this->getProgressUrl($website, $test_id),
                'test_input_action_url' => $this->generateUrl('test_cancel', array(
                    'website' => $website,
                    'test_id' => $test_id
                )),
                'website' => $website,
                'test_id' => $test_id,
                'public_site' => $this->container->getParameter('public_site'),
                'user' => $this->getUser(),
                'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                
            ));            
        }      
        
        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));            
        }       
        
        if (!in_array($test->getState(), $this->testFinishedStates)) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }
        
        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();        
        if (($remoteTestSummary->task_count - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount()) {
            $urlParameters = array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id                
            );
            
            if ($this->get('request')->query->has('output')) {
                $urlParameters['output'] = $this->get('request')->query->get('output');
            }
            
            return $this->redirect($this->generateUrl('app_results_preparing', $urlParameters, true));
        } else {
            $this->getTaskService()->getCollection($test);
        }      
        
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeObject) {
            if ($taskTypeObject->name == 'JS static analysis') {
                $taskTypes[] = 'JavaScript static analysis';
            } else {
                $taskTypes[] = $taskTypeObject->name;
            }                      
        }
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test' => $test,
            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
            'public_site' => $this->container->getParameter('public_site'),
            'filter' => $taskListFilter,
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),    
            'task_types' => $taskTypes,
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $this->getTestOptionsFromRemoteTestSummary($remoteTestSummary),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'default_css_validation_options' => array(
                'ignore-warnings' => 1,
                'vendor-extensions' => 'warn',
                'ignore-common-cdns' => 1                
            ),
            'default_js_static_analysis_options' => array(
                'ignore-common-cdns' => 1                
            ),            
            'test_options_title' => 'Re-check:'
        );
                       
        //$taskCollectionLength = ($taskListFilter == 'all') ? $remoteTestSummary->task_count : $this->getFilteredTaskCollectionLength($test, $this->getRequestValue('filter', 'all'));

        //if ($taskCollectionLength > 0 && $taskCollectionLength <= self::RESULTS_PAGE_LENGTH) {
            $remoteTaskIds = ($taskListFilter == 'all') ? null : $this->getFilteredTaskCollectionRemoteIds($test, $this->getRequestValue('filter', $taskListFilter));           
            $tasks = $this->getTaskService()->getCollection($test, $remoteTaskIds); 
       
            $viewData['tasks'] = $this->getTasksGroupedByUrl($tasks);
        //} else {
        //    $viewData['tasks'] = array();
        //}
            
        $this->setTemplate('SimplyTestableWebClientBundle:App:results.html.twig');
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
    }
    
    
    private function getTestOptionsFromRemoteTestSummary($remoteTestSummary) {
        $testOptions = array();
        
        foreach ($remoteTestSummary->task_types as $taskType) {
            $taskTypeName = $taskType->name;
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskTypeName));
            
            $testOptions[$taskTypeKey] = 1;
        }
        
        foreach($remoteTestSummary->task_type_options as $taskTypeName => $taskTypeOptionsSet) {
            $taskTypeKey = strtolower(str_replace(' ', '-', $taskTypeName));            
            
            foreach ($taskTypeOptionsSet as $taskTypeOptionKey => $taskTypeOptionValue) {
                $testOptions[$taskTypeKey.'-'.$taskTypeOptionKey] = $taskTypeOptionValue;
            }
        }
        
        foreach ($this->testOptionNamesAndDefaultValues as $testOptionName => $defaultValue) {
            if (!isset($testOptions[$testOptionName])) {
                $testOptions[$testOptionName] = '';
            }
        }
        
        return $testOptions;
    }
    
    
    /**
     * 
     * @param array $tasks
     * @return array
     */
    private function getTasksGroupedByUrl($tasks = array()) {
        $tasksGroupedByUrl = array();
        foreach ($tasks as $task) {
            $url = idn_to_utf8($task->getUrl());
            
            /* @var $task Task */
            if (!isset($tasksGroupedByUrl[$url])) {
                $tasksGroupedByUrl[$url] = array();
            }
            
            $tasksGroupedByUrl[$url][] = $task;
        }
        
        return $tasksGroupedByUrl;
    }
    
    
    private function getFilteredTaskCollectionLength(Test $test, $filter) {
        if ($filter == 'cancelled') {
            return $this->getTaskService()->getEntityRepository()->getCountByTestAndState($test, array('cancelled'));
        }
        
        if ($filter == 'without-errors') {
            return $this->getTaskService()->getEntityRepository()->getErrorFreeCountByTest($test);
        }        
        
        if ($filter == 'with-errors') {
            return $this->getTaskService()->getEntityRepository()->getErroredCountByTest($test);
        }        
        
        if ($filter == 'skipped') {
            return $this->getTaskService()->getEntityRepository()->getCountByTestAndState($test, array('skipped'));
        }          
        
        return null;
    }
    
    
    private function getFilteredTaskCollectionRemoteIds(Test $test, $filter) {        
        if ($filter == 'cancelled') {
            return $this->getTaskService()->getEntityRepository()->getRemoteIdByTestAndState($test, array('cancelled'));
        }
        
        if ($filter == 'without-errors') {
            return $this->getTaskService()->getEntityRepository()->getErrorFreeRemoteIdByTest($test, array('skipped', 'cancelled', 'in-progress', 'awaiting-cancellation'));
        }  
        
        if ($filter == 'with-errors') {
            return $this->getTaskService()->getEntityRepository()->getErroredRemoteIdByTest($test, array('skipped', 'cancelled', 'in-progress', 'awaiting-cancellation'));
        }  
        
        if ($filter == 'skipped') {
            return $this->getTaskService()->getEntityRepository()->getRemoteIdByTestAndState($test, array('skipped'));
        }         
        
        return null;      
    }
   
    
    /**
     *
     * @param string $flashKey
     * @return boolean
     */
    private function hasFlash($flashKey) {
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);        
        return is_array($flashMessages) && count($flashMessages) > 0;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService 
     */
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService 
     */
    private function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
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
     * @return \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private function getTestQueueService() {
        if (is_null($this->testQueueService)) {
            $this->testQueueService = $this->container->get('simplytestable.services.testqueueservice');
            $this->testQueueService->setApplicationRootDirectory($this->container->get('kernel')->getRootDir());
                    
        }
        
        return $this->testQueueService;

    }    
    
    
    /**
     *
     * @return \JMS\SerializerBundle\Serializer\Serializer
     */
    protected function getSerializer() {
        return $this->container->get('serializer');
    }   
    
    
    public function outdatedBrowserAction() {
        $publicSiteParameters = $this->container->getParameter('public_site');        
        return $this->redirect($publicSiteParameters['urls']['home']);
    }

    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory
     */
    private function getTaskOutputResultParserService() {
        return $this->container->get('simplytestable.services.taskoutputresultparserfactoryservice');
    }    

}
