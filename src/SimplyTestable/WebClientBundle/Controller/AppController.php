<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class AppController extends TestViewController
{   
    const RESULTS_PREPARATION_THRESHOLD = 100;    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;    
    
    
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
        
        $this->getUserService()->setUser($this->getUser());
        
//        if (!$this->isUserValid()) {            
//            return $this->redirect($this->generateUrl('sign_out_submit', array(), true));
//        }        
        
        $templateName = 'SimplyTestableWebClientBundle:App:index.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);

        $testStartError = $this->getFlash('test_start_error');        
        
        $currentTests = $this->getCurrentTests();
        $currentTestsHash = md5(json_encode($currentTests));
        
        $recentTests = $this->getRecentTests(3);
        $recentTestsHash = md5(json_encode($recentTests));        
        
        $testCancelledQueuedWebsite = $this->getFlash('test_cancelled_queued_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'test_start_error' => $testStartError,
            'current_tests_hash' => $currentTestsHash,
            'recent_tests_hash' => $recentTestsHash,            
            'test_cancelled_queued_website' => $testCancelledQueuedWebsite
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//        if ($response->isNotModified($this->getRequest())) {
//            return $response;
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);;
        
        $this->getTestOptionsAdapter()->setRequestData($this->defaultAndPersistentTestOptionsToParameterBag());
        if ($testStartError != '') {
            $this->getTestOptionsAdapter()->setInvertInvertableOptions(true);
        }        
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();        
        
//        return $this->render($templateName, array(
//            'test_start_error' => $testStartError,
//            'public_site' => $this->container->getParameter('public_site'),
//            'user' => $this->getUser(),
//            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
//            'current_tests' => $currentTests,            
//            'recent_tests' => $recentTests,
//            'website' => idn_to_utf8($this->getPersistentValue('website')),
//            'available_task_types' => $this->getAvailableTaskTypes(),
//            'test_options' => $testOptions->__toKeyArray(),
//            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
//            'js_static_analysis_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
//            'test_cancelled_queued_website' => $testCancelledQueuedWebsite,
//            'test_options_introduction' => $this->getTestOptionsIntroduction($testOptions)
//        ));         

        return $this->getCachableResponse($this->render($templateName, array(            
            'test_start_error' => $testStartError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'current_tests' => $currentTests,            
            'recent_tests' => $recentTests,
            'website' => idn_to_utf8($this->getPersistentValue('website')),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'test_cancelled_queued_website' => $testCancelledQueuedWebsite,
            'test_options_introduction' => $this->getTestOptionsIntroduction($testOptions)
        )), $cacheValidatorHeaders);        
    }
    
    private function getTestOptionsIntroduction(\SimplyTestable\WebClientBundle\Model\TestOptions $testOptions) {        
        $testOptionsIntroduction = 'Testing ';
        
        $availableTaskTypes = $this->getAvailableTaskTypes();
        
        $taskTypeIndex = 0;
        foreach ($availableTaskTypes as $taskTypeKey => $taskTypeName) {            
            $taskTypeIndex++;
            
            $testOptionsIntroduction .= '<span class="' . ($testOptions->hasTestType($taskTypeName) ? 'selected' : 'not-selected') . '">' . str_replace('JS ', 'JavaScript ', $taskTypeName) . '</span>';
            
            if ($taskTypeIndex === count($availableTaskTypes) - 1) {
                $testOptionsIntroduction .= ' and ';
            } else {
                if ($taskTypeIndex < count($availableTaskTypes)) {
                    $testOptionsIntroduction .= ', ';
                }                
            }
        }
        
        $testOptionsIntroduction .= '.';
        
        return $testOptionsIntroduction;
    }
    

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function defaultAndPersistentTestOptionsToParameterBag() {
        $testOptionsParameters = $this->container->getParameter('test_options');
        return new \Symfony\Component\HttpFoundation\ParameterBag($this->getPersistentValues($testOptionsParameters['names_and_default_values']));
    }    
    
    
    /**
     * 
     * @return array
     */
    private function getTestOptions() {
        $testOptionsParameters = $this->container->getParameter('test_options');
        $testOptions = $this->getPersistentValues($testOptionsParameters['names_and_default_values']);
        
        
        
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
        $this->getAvailableTaskTypeService()->setUser($this->getUser());
        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());
        
        return $this->getAvailableTaskTypeService()->get();
    }
    
    
    /**
     * 
     * @return array
     */
    private function getRecentTests($limit = 5) {                
//        if (!$this->isLoggedIn()) {
//            return array();
//        }
        
        $recentRemoteTests = $this->getTestService()->getRemoteTestService()->getList($limit, array('crawl'), array('rejected'));                
        $recentTests = array();
        
//        $recentRemoteTests = array_slice($recentRemoteTests, 0, 2);

        
        foreach ($recentRemoteTests as $remoteTest) {                        
            /* @var $remoteTest RemoteTest */           
            $currentTest = array();
            
            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);
            
            if ($remoteTest->getTaskCount() != $test->getTaskCount()) {
                $remoteTest = $this->getTestService()->getRemoteTestService()->get();        
                if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount()) {            
                    $currentTest['requires_results'] = true;
                } else {
                    $this->getTaskService()->getCollection($test);
                }
            } 
            

            
//            var_dump($test);
//            exit();
//            
//            if ($test['state'] == 'failed-no-sitemap' && isset($test['crawl'])) {                
//                $recentTestRemoteSummaries[$testIndex]['state'] = 'crawling';
//            }
//            
            //$remoteTestSummary->website_label = $this->getWebsiteLabel($remoteTestSummary->website);
//            $recentTestRemoteSummaries[$testIndex]['state_icon'] = $this->getTestStateIcon($recentTestRemoteSummaries[$testIndex]['state']);
//            $recentTestRemoteSummaries[$testIndex]['state_label_class'] = $this->getTestStateLabelClass($recentTestRemoteSummaries[$testIndex]['state']);
//            $recentTestRemoteSummaries[$testIndex]['completion_percent'] = $this->getCompletionPercent($test);

            $currentTest['test'] = $test;
            $currentTest['remote_test'] = $remoteTest;            
            
            if ($remoteTest->hasRejection()) {
                //$recentTests[] = $currentTest;
                
//                var_dump($remoteTest->getRejection()->getReason());
//                exit();
            }            
            
            $recentTests[] = $currentTest;
        }
        
//        var_dump($recentTestRemoteSummaries);
//        exit();
//        
        return $recentTests;
    }
    
    
    private function getCurrentTests() {        
        $jsonResource = $this->getTestService()->getRemoteTestService()->getCurrent();
        
        if (!$jsonResource instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
            return array();
        }        
        
        $currentTests = json_decode($jsonResource->getContent(), true);
        
        if (count($currentTests) === 0) {
            return $currentTests;
        }
        
        foreach ($currentTests as $testIndex => $test) {            
            if ($currentTests[0]['state'] == 'failed-no-sitemap' && isset($test['crawl'])) {                
                $currentTests[$testIndex]['state'] = 'crawling';
            }
            
            $currentTests[$testIndex]['website_label'] = $this->getWebsiteLabel($currentTests[$testIndex]['website']);
            $currentTests[$testIndex]['state_icon'] = $this->getTestStateIcon($currentTests[$testIndex]['state']);
            $currentTests[$testIndex]['state_label_class'] = $this->getTestStateLabelClass($currentTests[$testIndex]['state']);
            $currentTests[$testIndex]['completion_percent'] = $this->getCompletionPercent($test);
        }    
        
        return $currentTests;
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
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress-queued.html.twig');
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
        if (isset($remoteTestSummary['crawl']) && $remoteTestSummary['state'] == 'failed-no-sitemap') {
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
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());        
                
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
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();
        
        $localTaskCount = $test->getTaskCount();      
        $completionPercent = round(($localTaskCount / $remoteTest->getTaskCount()) * 100);
        $remainingTasksToRetrieveCount = $remoteTest->getTaskCount() - $localTaskCount;
        
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
            'remote_task_count' => $remoteTest->getTaskCount()
        ));     
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
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
        }
        
        return $this->testOptionsAdapter;
    }    

}
