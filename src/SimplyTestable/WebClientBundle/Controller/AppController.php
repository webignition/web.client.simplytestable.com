<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class AppController extends TestViewController
{   
    const RESULTS_PREPARATION_THRESHOLD = 10;    
    
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
    
    public function indexAction()
    {                   
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $this->getUserService()->setUser($this->getUser());
        
        if (!$this->isUserValid()) {            
            return $this->redirect($this->generateUrl('sign_out_submit', array(), true));
        }

        $testStartError = $this->getFlash('test_start_error');                
        $currentTests = $this->getCurrentTests();               
        $finishedTests = $this->getFinishedTests();      
        
        $templateName = 'SimplyTestableWebClientBundle:App:index.html.twig';
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'template_last_modified_date' => $this->getTemplateLastModifiedDate($templateName)->format('Y-m-d H:i:s'),
            'test_start_error' => $testStartError,
            'current_tests_hash' => md5(json_encode($currentTests)),
            'finished_tests_hash' => $finishedTests->getHash()
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }
        
        $cacheValidatorHeaders->setLastModifiedDate(new \DateTime());
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);;
        
        $this->getTestOptionsAdapter()->setRequestData($this->defaultAndPersistentTestOptionsToParameterBag());
        if ($testStartError != '') {
            $this->getTestOptionsAdapter()->setInvertInvertableOptions(true);
        }        
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();        

        return $this->getCachableResponse($this->render($templateName, array(            
            'test_start_error' => $testStartError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'current_tests' => $currentTests,            
            'test_list' => $finishedTests,
            'website' => idn_to_utf8($this->getPersistentValue('website')),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
            'test_options_introduction' => $this->getTestOptionsIntroduction($testOptions),
            'test_authentication_introduction' => $this->getTestAuthenticationIntroduction($testOptions)
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
    
    
    private function getTestAuthenticationIntroduction(\SimplyTestable\WebClientBundle\Model\TestOptions $testOptions) {        
        if (!$testOptions->hasFeatureOptions('http-authentication')) {
            return 'This site or page does not require authentication.';
        }
        
        $httpAuthenticationOptions = $testOptions->getFeatureOptions('http-authentication');
        return (isset($httpAuthenticationOptions['http-auth-username']) && $httpAuthenticationOptions['http-auth-username'] != '')
            ? 'This site or page requires authentication.'
            : 'This site or page does not require authentication.';
    }
    

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function defaultAndPersistentTestOptionsToParameterBag() {
        $testOptionsParameters = $this->container->getParameter('test_options');        
        return new \Symfony\Component\HttpFoundation\ParameterBag($this->getPersistentValues($testOptionsParameters['names_and_default_values']));
    }    
    
    
//    /**
//     * 
//     * @return array
//     */
//    private function getTestOptions() {
//        $testOptionsParameters = $this->container->getParameter('test_options');
//        $testOptions = $this->getPersistentValues($testOptionsParameters['names_and_default_values']);
//        
//        
//        
//        return $testOptions;
//    }
    
    
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
    
    
    /**
     * 
     * @return TestList
     */
    private function getFinishedTests($limit = 3, $offset = 0) {                        
        $testList = $this->getTestService()->getRemoteTestService()->getFinished($limit, $offset);
        
        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];
            
            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);
            
            $testList->addTest($test);
            
            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->getTaskService()->getCollection($test);
                } else {
                    if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) - $test->getTaskCount()) {
                        $this->getTaskService()->getCollection($test);
                    }                   
                }
            }
        }
        
        return $testList;
    }
    
    
    private function getCurrentTests() {
        $testList = $this->getTestService()->getRemoteTestService()->getCurrent();
        if ($testList->isEmpty()) {
            return array();
        }
        
        $tests = array();
        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];            
            $currentTest = $remoteTest->getArraySource();
            
            if ($currentTest['state'] == 'failed-no-sitemap' && isset($currentTest['crawl'])) {                
                $currentTest['state'] = 'crawling';
            }            

            $currentTest['state_label_class'] = $this->getTestStateLabelClass($currentTest['state']);
            $currentTest['state_icon'] = $this->getTestStateIcon($currentTest['state']);
            $currentTest['website_label'] = $this->getWebsiteLabel($currentTest['website']);
            $currentTest['completion_percent'] = $remoteTest->getCompletionPercent();
            
            $tests[] = $currentTest;
        }
        
        return $tests;
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
    
    
    public function prepareResultsAction($website, $test_id)
    {              
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
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
    
    
    public function prepareResultsStatsAction($website, $test_id)
    {        
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());        
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }
        
        $test = $testRetrievalOutcome->getTest();

        if (!in_array($test->getState(), $this->testFinishedStates)) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }        
        
        if (!$test->hasTaskIds()) {
            $this->getTaskService()->getRemoteTaskIds($test);
        }  
        
        $remoteTest = $this->getTestService()->getRemoteTestService()->get();
        
        $localTaskCount = $test->getTaskCount();      
        $completionPercent = round(($localTaskCount / $remoteTest->getTaskCount()) * 100);
        $remainingTasksToRetrieveCount = $remoteTest->getTaskCount() - $localTaskCount;        
        
        return $this->getUncacheableResponse(new Response($this->getSerializer()->serialize(array(
            'id' => $test->getTestId(),
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remainingTasksToRetrieveCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTest->getTaskCount()            
        ), 'json')));
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
            
            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }
        }
        
        return $this->testOptionsAdapter;
    }    
    
    
    
    public function currentAction() {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());                
        return $this->getUncacheableResponse(new Response($this->getSerializer()->serialize($this->getCurrentTests(), 'json')));       
    }
    
    
    public function currentContentAction() {        
        $this->getUserService()->setUser($this->getUser());           
        
        $this->setTemplate('SimplyTestableWebClientBundle:Partials:current-content.html.twig');        
        return $this->getUncacheableResponse($this->render('SimplyTestableWebClientBundle:Partials:current-content.html.twig', array(            
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
             'current_tests' => $this->getCurrentTests()
         )));
       
    }
    
    public function finishedContentAction() {        
        $this->getUserService()->setUser($this->getUser());           
        
        $this->setTemplate('SimplyTestableWebClientBundle:Partials:finished-test-list.html.twig');        
        return $this->sendResponse(array(            
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'test_list' => $this->getFinishedTests()
        ));         
    }

}
