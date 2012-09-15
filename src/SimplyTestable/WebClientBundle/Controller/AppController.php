<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AppController extends BaseViewController
{
    private $progressStates = array(
        'new',
        'preparing',
        'queued',        
        'in-progress'        
    );
    
    private $finishedStates = array(
        'cancelled',
        'completed',
        'no-sitemap'
    );
    
    private $testStateLabelMap = array(
        'new' => 'New',
        'queued' => 'Queued',
        'preparing' => 'Running',
        'in-progress' => 'Running'        
    );
    
    private $testStateIconMap = array(
        'new' => 'icon-off',
        'queued' => 'icon-off',
        'queued-for-assignment' => 'icon-off',
        'preparing' => 'icon-play-circle',
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
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('test_start_error', ($hasTestStartError) ? 'true' : 'false');
        
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
            'public_site' => $this->container->getParameter('public_site')
        ));
        
        return $this->getCachableResponse($this->render($templateName, array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $hasTestStartError,
            'public_site' => $this->container->getParameter('public_site')
        )), $cacheValidatorHeaders);
    }   
    
    
    public function progressAction($website, $test_id) {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        if (!$this->getTestService()->has($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }

        $requestTaskIds = $this->getRequestTaskIds();        
        
        $test = $this->getTestService()->get($website, $test_id, $requestTaskIds);
        
        if (in_array($test->getState(), $this->finishedStates)) {
            return $this->redirect($this->getResultsUrl($website, $test_id));
        }
        
        $tasksByUrl = $this->getTasksByUrl($this->getTestUrls($test), $test->getTasks(), $requestTaskIds);
        $taskCount = $test->getTasks()->count();
        $taskCountByState = $this->getTaskCountByState($test);
        $completionPercent = $test->getCompletionPercent();
        
        $test->clearTasks();        

        $viewData = array(
            'this_url' => $this->getProgressUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => $test_id
            )),
            'test' => $test,
            'tasksByUrl' => $tasksByUrl,
            'state_label' => $this->testStateLabelMap[$test->getState()].': ',
            'state_icon' => $this->testStateIconMap[$test->getState()],
            'taskCount' => $taskCount,
            'taskCountByState' => $taskCountByState,
            'completionPercent' => $completionPercent,
            'testId' => $test_id,
            'public_site' => $this->container->getParameter('public_site'),
            'urlCount' => $test->getUrlCount()
        );          
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
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
     * @param Test $test
     * @return array 
     */
    private function getTaskCountByState(Test $test) {        
        $taskStates = array(
            'in-progress' => 'in-progress',
            'queued' => 'queued',
            'queued-for-assignment' => 'queued',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'awaiting-cancellation' => 'cancelled'
        );
        
        $taskCountByState = array();        
        
        foreach ($taskStates as $taskState => $translatedState) {
            if (!isset($taskCountByState[$translatedState])) {
                $taskCountByState[$translatedState] = 0;
            }
            
            $taskCountByState[$translatedState] += $test->getTaskCountByState($taskState);
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
            return $this->redirect($this->generateUrl('app', array(), true));
        }        
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
//        if ($response->isNotModified($this->getRequest())) {
//            return $response;
//        }
        
        $test = $this->getTestService()->get($website, $test_id);      
        if (in_array($test->getState(), $this->progressStates)) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }
        
        foreach ($test->getTasks() as $task) {
            /* @var $task Task */
            if ($task->getState() == 'completed' && !$task->hasOutput()) {
                if ($this->getTaskOutputService()->has($test, $task)) {
                    $this->getTaskOutputService()->get($test, $task);
                } 
            } elseif ($test->getState() == 'completed' && ($task->getState() == 'queued' || $task->getState() == 'in-progress')) {
                $this->getTaskService()->markCancelled($task);
            }
        }
        
        $viewData = array(
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test' => $test,          
            'tasksByUrl' => $this->getTasksByUrl($this->getTestUrls($test), $test->getTasks()),
            'testId' => $test_id,
            'taskCountByState' => $this->getTaskCountByState($test),
            'taskErrorCount' => $this->getTaskErrorCount($test),
            'erroredTaskCount' => $this->getErroredTaskCount($test),
            'public_site' => $this->container->getParameter('public_site')
        );
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:results.html.twig');     
        
        return $this->getCachableResponse(
                $this->sendResponse($viewData),
                $cacheValidatorHeaders
        ); 
    }
    
    private function getErroredTaskCount(Test $test) {
        $totalTaskErrorCount = 0;
        
        foreach ($test->getTasks() as $task) {
            /* @var $task Task */
            if ($task->getState() == 'completed' && $task->hasOutput()) {
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
            if ($task->getState() == 'completed' && $task->hasOutput()) {
                $this->getTaskOutputService()->setParsedOutput($task);
                $taskErrorCounts[$task->getId()] = $task->getOutput()->getResult()->getErrorCount();
            }
        }
        
        return $taskErrorCounts;
    }    
    
    
    /**
     * Get the progress page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */
    private function getProgressUrl($website, $test_id) {
        return $this->generateUrl(
            'app_progress',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
    }
    
    
    /**
     * Get the results page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */    
    private function getResultsUrl($website, $test_id) {
        return $this->generateUrl(
            'app_results',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
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

}
