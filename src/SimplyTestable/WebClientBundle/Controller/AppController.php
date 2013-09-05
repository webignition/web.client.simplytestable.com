<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class AppController extends TestViewController
{       
    private $testFinishedStates = array(
        'cancelled',
        'completed',
        'failed-no-sitemap',
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
            'test_options' => $this->getTestOptions(),
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
    private function getTestOptions() {
        $testOptionsParameters = $this->container->getParameter('test_options');        
        $testOptions = $this->getPersistentValues($testOptionsParameters['names_and_default_values']);
        
        foreach ($testOptionsParameters['invert_option_keys'] as $optionName) {
            if (isset($testOptions[$optionName])) {
                $testOptions[$optionName] = ($testOptions[$optionName] ==='1') ? '0' : '1';
            } else {
                $testOptions[$optionName] = '1';
            }
        }
        
        return $testOptions;
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
        
        if ($this->isEarlyAccessUser() && is_array($allAvailableTaskTypes['early_access'])) {
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
        
        $jsonResource = $this->getTestService()->getList($limit, array('crawl'));
        
        if (!$jsonResource instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
            return array();
        }
        
        $recentTests = json_decode($jsonResource->getContent(), true);
        
        foreach ($recentTests as $testIndex => $test) {            
            if ($test['state'] == 'failed-no-sitemap' && isset($test['crawl'])) {                
                $recentTests[$testIndex]['state'] = 'crawling';
            }
            
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
                
            case 'crawling':
                return 'icon-refresh';
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
            case 'crawling':
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
            'state_label' => $this->testStateLabelMap['queued'],
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
        if ($remoteTestSummary['state'] == 'failed-no-sitemap' && isset($remoteTestSummary['crawl'])) {
            if ($remoteTestSummary['crawl']['discovered_url_count'] == 0) {
                return 0;
            }  

            return round(($remoteTestSummary['crawl']['discovered_url_count'] / $remoteTestSummary['crawl']['limit']) * 100);               
        }
        
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
        $this->getTestService()->setUser($this->getUser());        
                
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();

        if (!in_array($test->getState(), $this->testFinishedStates)) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
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
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService 
     */
    private function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
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
    
    
    public function outdatedBrowserAction() {
        $publicSiteParameters = $this->container->getParameter('public_site');        
        return $this->redirect($publicSiteParameters['urls']['home']);
    }

}
