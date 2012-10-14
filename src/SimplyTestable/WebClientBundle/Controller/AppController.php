<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AppController extends BaseViewController
{
    const RESULTS_PAGE_LENGTH = 100;
    
    
    private $progressStates = array(
        'new',
        'preparing',
        'queued',        
        'in-progress'        
    );
    
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
    
    public function indexAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $templateName = 'SimplyTestableWebClientBundle:App:index.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $hasTestStartError = $this->hasFlash('test_start_error');
        $hasTestStartBlockedWebsiteError = $this->hasFlash('test_start_error_blocked_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('test_start_error', ($hasTestStartError) ? 'true' : 'false');
        $cacheValidatorIdentifier->setParameter('test_start_error_blocked_website', ($hasTestStartBlockedWebsiteError) ? 'true' : 'false');
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        if ($cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);
        
        return $this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $hasTestStartError,
            'test_start_error_blocked_website' => $hasTestStartBlockedWebsiteError,
            'website' => $this->getFlash('website'),
            'public_site' => $this->container->getParameter('public_site')
        ));
        
        return $this->getCachableResponse($this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $hasTestStartError,
            'test_start_error_blocked_website' => $hasTestStartBlockedWebsiteError,
            'public_site' => $this->container->getParameter('public_site')
        )), $cacheValidatorHeaders);
    }
    
    
    public function progressAction($website, $test_id) {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        } 

        if (!$this->getTestService()->has($website, $test_id)) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        } 
        
        $test = $this->getTestService()->get($website, $test_id);
        
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
        
        $viewData = array(
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
            'public_site' => $this->container->getParameter('public_site')
        );          
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
    }
    
    private function getRemoteTestSummaryArray($remoteTestSummary) {        
        $remoteTestSummaryArray = (array)$remoteTestSummary;
        
        foreach ($remoteTestSummaryArray as $key => $value) {
            if ($value instanceof \stdClass){
                $remoteTestSummaryArray[$key] = (array)$value;
            }
        }
        
        return $remoteTestSummaryArray;
    }
    
    
    /**
     *
     * @param \stdClass $remoteTestSummary
     * @return int 
     */
    private function getCompletionPercent($remoteTestSummary) {
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
    
    private function getRequestTaskIds() {
        if (!$this->getRequest()->query->has('taskIds')) {
            return null;
        }
        
        $rawRequestTaskIds = explode(',', $this->getRequest()->query->get('taskIds'));
        $requestTaskIds = array();
        
        foreach ($rawRequestTaskIds as $requestTaskId) {
            if (ctype_digit($requestTaskId)) {
                $requestTaskIds[] = (int)$requestTaskId;
            }
        }
        
        return (count($requestTaskIds) > 0) ? $requestTaskIds : null;
    }    
    
    
    /**
     *
     * @param array $urls
     * @param array $tasks
     * @param array $taskIds limit output to specified tasks
     * @return type 
     */
    private function getTasksByUrl($urls, $tasks, $taskIds = null) {
        $tasksByUrl = array();
        
        foreach ($tasks as $task) {
            if ($this->isTaskToBeIncludedInOutput($task, $taskIds)) {
                if (!isset($tasksByUrl[$task->getUrl()])) {
                    $tasksByUrl[$task->getUrl()] = array();
                }
                
                $tasksByUrl[$task->getUrl()][] = $task;
            }
        }
        
        return $tasksByUrl;
    }
    
    private function isTaskToBeIncludedInOutput(Task $task, $inclduedTaskIds = null) {
        if (!is_array($inclduedTaskIds)) {
            return true;
        }
        
        return in_array($task->getId(), $inclduedTaskIds);
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
     * @param Test $test
     * @return array 
     */
    private function getTestUrls(Test $test) {
        $urls = array();
        $urlListResponse = $this->getTestService()->getUrls($test);
        
        if (!$urlListResponse) {
            return $urls;
        }
        
        $urlListContentObject = $urlListResponse->getContentObject();
        foreach ($urlListContentObject as $urlObject) {
            $urls[] = $urlObject->url;
        }
        
        return $urls;           
    }
    
    
    public function resultsAction($website, $test_id) {                
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        if (!$this->getTestService()->has($website, $test_id)) {
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
        
        $test = $this->getTestService()->get($website, $test_id);
        
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
        
        $viewData = array(
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test' => $test,
            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
            'public_site' => $this->container->getParameter('public_site'),
            'filter' => $taskListFilter          
        );
                       
        //$taskCollectionLength = ($taskListFilter == 'all') ? $remoteTestSummary->task_count : $this->getFilteredTaskCollectionLength($test, $this->getRequestValue('filter', 'all'));

        //if ($taskCollectionLength > 0 && $taskCollectionLength <= self::RESULTS_PAGE_LENGTH) {
            $remoteTaskIds = ($taskListFilter == 'all') ? null : $this->getFilteredTaskCollectionRemoteIds($test, $this->getRequestValue('filter', $taskListFilter));           
            $tasks = $this->getTaskService()->getCollection($test, $remoteTaskIds); 
            
            foreach ($tasks as $taskIndex => $task) {                
                if (($task->getState() == 'completed' || $task->getState() == 'failed') && $task->hasOutput()) {           
                    $parser = $this->getTaskOutputResultParserService()->getParser($task->getOutput());
                    $parser->setOutput($task->getOutput());

                    $task->getOutput()->setResult($parser->getResult());
                }
            } 
            
            $viewData['tasks'] = $tasks;
        //} else {
        //    $viewData['tasks'] = array();
        //}
            
        $this->setTemplate('SimplyTestableWebClientBundle:App:results.html.twig');
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
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
    
    private function getErroredTaskCount(Test $test) {
        $totalTaskErrorCount = 0;
        
        foreach ($test->getTasks() as $task) {
            /* @var $task Task */
            if (($task->getState() == 'completed' || substr($task->getState(), 0, strlen('failed')) == 'failed') && $task->hasOutput()) {
                $this->getTaskOutputService()->setParsedOutput($task);
                if ($task->getOutput()->getResult()->hasErrors()) {
                    $totalTaskErrorCount += 1;
                }
            }
        }
        
        return $totalTaskErrorCount;        
    }
    
    
    /**
     *
     * @param Test $test
     * @return array
     */
    private function getTaskErrorCount(Test $test) {
        $taskErrorCounts = array();
        
        foreach ($test->getTasks() as $task) {
            /* @var $task Task */            
            if (($task->getState() == 'completed' || substr($task->getState(), 0, strlen('failed')) == 'failed') && $task->hasOutput()) {
                $this->getTaskOutputService()->setParsedOutput($task);                
                $taskErrorCounts[$task->getId()] = $task->getOutput()->getResult()->getErrorCount();
            }
        }
        
        return $taskErrorCounts;
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
     * @param type $flashKey
     * @param type $messageIndex
     * @return string|null 
     */
    private function getFlash($flashKey, $messageIndex = 0) {        
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);         
        if (!isset($flashMessages[$messageIndex])) {
            return false;
        }
        
        return $flashMessages[$messageIndex];
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
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutputService 
     */
    private function getTaskOutputService() {
        return $this->container->get('simplytestable.services.taskoutputservice');
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
    
    public function isUsingOldIE() {        
        $browserInfo =  $this->container->get('jbi_browscap.browscap')->getBrowser($this->getRequest()->server->get('HTTP_USER_AGENT'));                
        return ($browserInfo->Browser == 'IE' && $browserInfo->MajorVer < 8);     
    }    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory
     */
    private function getTaskOutputResultParserService() {
        return $this->container->get('simplytestable.services.taskoutputresultparserfactoryservice');
    }    

}
