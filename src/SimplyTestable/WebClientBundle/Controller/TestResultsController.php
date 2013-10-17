<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class TestResultsController extends TestViewController
{    
    const RESULTS_PAGE_LENGTH = 100;
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
        'rejected'
    );
    
    
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
    
    
    public function failedNoUrlsDetectedAction($website, $test_id) {
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
     
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        
        $this->getTestService()->setUser($this->getUser());        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();
        
        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));            
        }       
        
        if ($test->getState() !== 'failed-no-sitemap') {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }
        
        if (!$this->getUserService()->isPublicUser($this->getUser())) {            
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }
        
        $redirectParameters = json_encode(array(
            'route' => 'app_progress',
            'parameters' => array(
                'website' => $website,
                'test_id' => $test_id                        
            )
        ));       
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'test' => $test,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'redirect' => base64_encode($redirectParameters)
        );
            
        $this->setTemplate('SimplyTestableWebClientBundle:App:results-failed-no-sitemap.html.twig');
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
    }
    
    
    public function rejectedAction($website, $test_id) {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
     
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        
        $this->getTestService()->setUser($this->getUser());        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();
        
        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));            
        }       
        
        if ($test->getState() !== 'rejected') {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }
        
        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();         
        $userSummary = ($remoteTestSummary->rejection->constraint->name == 'credits_per_month')
                ? $this->getUserService()->getSummary($this->getUser())->getContentObject()
                : null;
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'test' => $test,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'userSummary' => $userSummary,
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'remote_test_summary' => $remoteTestSummary
        );

            
        $this->setTemplate('SimplyTestableWebClientBundle:App:results-rejected.html.twig');
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
    }    
    
    
    public function indexAction($website, $test_id) {                
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $taskOutcomeFilter = $this->getRequestValue('filter', 'with-errors');
        $taskTypeFilter = $this->getRequestValue('type', null);
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        $cacheValidatorIdentifier->setParameter('filter', $taskOutcomeFilter);
        $cacheValidatorIdentifier->setParameter('type', $taskTypeFilter);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        
        $this->getTestService()->setUser($this->getUser());        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }      
        
        $test = $testRetrievalOutcome->getTest();                
        if ($test->getState() == 'failed-no-sitemap') {            
            return $this->redirect($this->generateUrl('app_results_failed_no_urls_detected', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));             
        }
        
        if ($test->getState() == 'rejected') {            
            return $this->redirect($this->generateUrl('app_results_rejected', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));             
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
        
        $this->getTestOptionsAdapter()->setRequestData($this->remoteTestSummaryTestOptionsToParameterBag($remoteTestSummary));
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test' => $test,
            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
            'public_site' => $this->container->getParameter('public_site'),
            'filter' => $taskOutcomeFilter,
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),    
            'task_types' => $taskTypes,
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'default_css_validation_options' => array(
                'ignore-warnings' => 1,
                'vendor-extensions' => 'warn',
                'ignore-common-cdns' => 1                
            ),
            'default_js_static_analysis_options' => array(
                'ignore-common-cdns' => 1,
                'jslint-option-maxerr' => 50,
                'jslint-option-indent' => 4,
                'jslint-option-maxlen' => 256
            ),
            'test_options_introduction' => $this->getTestOptionsIntroduction($testOptions)
        );
                       
        //$taskCollectionLength = ($taskListFilter == 'all') ? $remoteTestSummary->task_count : $this->getFilteredTaskCollectionLength($test, $this->getRequestValue('filter', 'all'));

        //if ($taskCollectionLength > 0 && $taskCollectionLength <= self::RESULTS_PAGE_LENGTH) {        
        
            $remoteTaskIds = ($taskOutcomeFilter == 'all' && is_null($taskTypeFilter))
                    ? null
                    : $this->getFilteredTaskCollectionRemoteIds(
                            $test,
                            $taskOutcomeFilter,
                            $taskTypeFilter
                      );           
            
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
    
    private function getTestOptionsIntroduction(\SimplyTestable\WebClientBundle\Model\TestOptions $testOptions) {        
        $testOptionsIntroduction = 'Tested ';
        
        $allAvailableTaskTypes = $this->container->getParameter('available_task_types');
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
    
    
    private function getFilteredTaskCollectionRemoteIds(Test $test, $taskOutcomeFilter, $taskTypeFilter) {        
        $this->getTaskCollectionFilterService()->setTest($test);
        $this->getTaskCollectionFilterService()->setOutcomeFilter($taskOutcomeFilter);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);
        
        return $this->getTaskCollectionFilterService()->getRemoteIds();    
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService 
     */
    private function getTaskCollectionFilterService() {
        return $this->container->get('simplytestable.services.taskcollectionfilterservice');
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
