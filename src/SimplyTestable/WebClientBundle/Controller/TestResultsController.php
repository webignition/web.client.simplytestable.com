<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

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
    private function getJsStaticAnalysisCommonCdnsToIgnore() {
        if (!$this->container->hasParameter('js-static-analysis-ignore-common-cdns')) {
            return array();
        }
        
        return $this->container->getParameter('js-static-analysis-ignore-common-cdns');
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
        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());        
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
        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());        
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
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();        
        $userSummary = $this->getUserService()->getSummary($this->getUser())->getContentObject();        
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'test' => $test,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'userSummary' => $userSummary,
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'remote_test' => $remoteTest
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
        if (trim($taskTypeFilter) == '') {
            $taskTypeFilter = null;
        }
        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {            
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();                
        $isOwner = $this->getTestService()->getRemoteTestService()->owns($test);        
        $isPublic = $this->getTestService()->getRemoteTestService()->isPublic();
        $isPublicUserTest = $test->getUser() == $this->getUserService()->getPublicUser()->getUsername();
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        $cacheValidatorIdentifier->setParameter('filter', $taskOutcomeFilter);
        $cacheValidatorIdentifier->setParameter('type', $taskTypeFilter);
        $cacheValidatorIdentifier->setParameter('isOwner', $isOwner);
        $cacheValidatorIdentifier->setParameter('isPublic', $isPublic);
        $cacheValidatorIdentifier->setParameter('isPublicUserTest', $isPublicUserTest);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }        
        
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
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();        
        if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount()) {            
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
        
        $taskTypes = $remoteTest->getTaskTypes();
        foreach ($taskTypes as $taskTypeIndex => $taskType) {
            if ($taskType == 'JS static analysis') {
                $taskTypes[$taskTypeIndex] = 'JavaScript static analysis';
            }                  
        }
        
        $this->getTestOptionsAdapter()->setRequestData($remoteTest->getOptions());
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();   
        
        $viewData = array(
            'website' => idn_to_utf8($website),
            'formatted_website' => idn_to_utf8($this->getSchemelessUrl($website)),
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test' => $test,
            'remote_test' => $remoteTest,
            'public_site' => $this->container->getParameter('public_site'),
            'filter' => $taskOutcomeFilter,
            'filter_label' => ucwords(str_replace('-', ' ', $taskOutcomeFilter)),
            'type' => $taskTypeFilter,
            'type_label' => $this->getTaskTypeLabel($taskTypeFilter),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),    
            'is_owner' => $isOwner,
            'is_public' => $isPublic,
            'is_public_user_test' => $isPublicUserTest,
            'task_types' => $taskTypes,
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
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
            'test_options_introduction' => $this->getTestOptionsIntroduction($testOptions),
            'filtered_task_counts' => $this->getFilteredTaskCounts($test, $taskTypeFilter),
            'test_authentication_enabled' => $this->getTestAuthenticationIsEnabled($remoteTest),
            'test_cookies_enabled' => $this->getTestCookiesIsEnabled($remoteTest)
        );
                       
        //$taskCollectionLength = ($taskListFilter == 'all') ? $remoteTest->getTaskCount() : $this->getFilteredTaskCollectionLength($test, $this->getRequestValue('filter', 'all'));

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
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest $remoteTest
     * @return boolean
     */
    private function getTestAuthenticationIsEnabled(RemoteTest $remoteTest) {        
        return $remoteTest->hasParameter('http-auth-username');
    } 
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest $remoteTest
     * @return boolean
     */
    private function getTestCookiesIsEnabled(RemoteTest $remoteTest) {        
        return $remoteTest->hasParameter('cookies');
    }
    
    
    private function getTaskTypeLabel($taskTypeFilter) {
        if (is_null($taskTypeFilter)) {
            return 'All';
        }
        
        $taskTypeLabel = str_replace(array('Css', 'Html'), array('CSS', 'HTML'), ucwords($taskTypeFilter));
        
        return $taskTypeLabel;
    }
    
    private function getTestOptionsIntroduction(\SimplyTestable\WebClientBundle\Model\TestOptions $testOptions) {        
        $testOptionsIntroduction = 'Testing ';
        
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
            $url = rawurldecode(idn_to_utf8($task->getUrl()));
            
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
        if ($taskTypeFilter == 'javascript static analysis') {
            $taskTypeFilter = 'js static analysis';
        }
        
        $this->getTaskCollectionFilterService()->setTest($test);
        $this->getTaskCollectionFilterService()->setOutcomeFilter($taskOutcomeFilter);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);
        
        return $this->getTaskCollectionFilterService()->getRemoteIds();    
    }
    
    
    private function getFilteredTaskCounts(Test $test, $taskTypeFilter) {        
        if ($taskTypeFilter == 'javascript static analysis') {
            $taskTypeFilter = 'js static analysis';
        }        
        
        $this->getTaskCollectionFilterService()->setTest($test);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);

        $filteredTaskCounts = array();        
        
        $filteredTaskCounts['all'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        $this->getTaskCollectionFilterService()->setOutcomeFilter('with-errors');
        $filteredTaskCounts['with_errors'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        $this->getTaskCollectionFilterService()->setOutcomeFilter('with-warnings');
        $filteredTaskCounts['with_warnings'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        $this->getTaskCollectionFilterService()->setOutcomeFilter('without-errors');
        $filteredTaskCounts['without_errors'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        $this->getTaskCollectionFilterService()->setOutcomeFilter('skipped');
        $filteredTaskCounts['skipped'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        $this->getTaskCollectionFilterService()->setOutcomeFilter('cancelled');
        $filteredTaskCounts['cancelled'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();
        
        return $filteredTaskCounts;
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
            
            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }            
        }
        
        return $this->testOptionsAdapter;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    } 
    
    
    public function finishedSummaryAction($website, $test_id) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();                
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }       
        
        $viewData = array(
            'test' => array(
                'test' => $test,
                'remote_test' => $this->getTestService()->getRemoteTestService()->get(),
            )
        );

        $this->setTemplate('SimplyTestableWebClientBundle:Partials:finished-job-summary.html.twig');  
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
    }

}
