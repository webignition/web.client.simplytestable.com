<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends TestViewController
{  
    const DEFAULT_UNRETRIEVED_TASKID_LIMIT = 100;
    const MAX_UNRETRIEVED_TASKID_LIMIT = 1000;
    
    private $finishedStates = array(
        'cancelled',
        'completed',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached'      
    );     
    
    private $failedStates = array(
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached'      
    );       
    
   
    public function collectionAction($website, $test_id) {
        $this->getTestService()->setUser($this->getUser());
        
        try {            
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());        
            $taskIds = $this->getRequestTaskIds();               
            $tasks = $this->getTaskService()->getCollection($test, $taskIds);

            foreach ($tasks as $task) {
                if (in_array($task->getState(), $this->finishedStates)) {
                    if ($task->hasOutput()) {             
                        $parser = $this->getTaskOutputResultParserService()->getParser($task->getOutput());
                        $parser->setOutput($task->getOutput());

                        $task->getOutput()->setResult($parser->getResult());
                    }
                }
            }

            return new Response($this->getSerializer()->serialize($tasks, 'json'));        
        } catch (\SimplyTestable\WebClientBundle\Exception\UserServiceException $userServiceException) {
            return $this->sendNotFoundResponse();            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        } catch (\Guzzle\Http\Exception\RequestException $requestException)  {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }
    }
    
    
    public function idCollectionAction($website, $test_id) {        
        $this->getTestService()->setUser($this->getUser());
        
        try {            
            $test = $this->getTestService()->get($website, $test_id, $this->getUser());        
            $taskIds = $this->getTaskService()->getRemoteTaskIds($test);

            return new Response($this->getSerializer()->serialize($taskIds, 'json'));          
        } catch (\SimplyTestable\WebClientBundle\Exception\UserServiceException $userServiceException) {
            return $this->sendNotFoundResponse();            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        } catch (\Guzzle\Http\Exception\RequestException $requestException)  {
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }
    }    
    
    
    public function unretrievedIdCollectionAction($website, $test_id, $limit = null) {
        $this->getTestService()->setUser($this->getUser());
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->sendNotFoundResponse();
        }
        
        $test = $this->getTestService()->get($website, $test_id, $this->getUser());
        $limit = filter_var($limit, FILTER_VALIDATE_INT);
        if ($limit === false) {
            $limit = self::DEFAULT_UNRETRIEVED_TASKID_LIMIT;
        }
        
        if ($limit > self::MAX_UNRETRIEVED_TASKID_LIMIT) {
            $limit = self::MAX_UNRETRIEVED_TASKID_LIMIT;
        }
        
        $taskIds = $this->getTaskService()->getUnretrievedRemoteTaskIds($test, $limit);//
        return new Response($this->getSerializer()->serialize($taskIds, 'json'));        
    }
    
    
    /**
     *
     * @return array|null
     */
    private function getRequestTaskIds() {        
        $requestTaskIds = $this->getRequestValue('taskIds');        
        $taskIds = array();
        
        if (substr_count($requestTaskIds, ':')) {
            $rangeLimits = explode(':', $requestTaskIds);
            
            for ($i = $rangeLimits[0]; $i<=$rangeLimits[1]; $i++) {
                $taskIds[] = $i;
            }
        } else {
            $rawRequestTaskIds = explode(',', $requestTaskIds);

            foreach ($rawRequestTaskIds as $requestTaskId) {
                if (ctype_digit($requestTaskId)) {
                    $taskIds[] = (int)$requestTaskId;
                }
            }            
        }
        
        return (count($taskIds) > 0) ? $taskIds : null;
    }    
  
    public function resultsAction($website, $test_id, $task_id) {        
        $this->getTestService()->setUser($this->getUser());
        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'website' => $website,
            'test_id' => $test_id,
            'task_id' => $task_id
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
//        if ($response->isNotModified($this->getRequest())) {
//            return $response;
//        }        
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }
        
        $test = $testRetrievalOutcome->getTest();        
        $task = $this->getTaskService()->get($test, $task_id);
        
        if (is_null($task)) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            )));            
        }
        
        $this->getCssValidationErrorsGroupedByRef($task);         
        
        $viewData = array(
                'test' => $test,
                'task' => $task,
                'public_site' => $this->container->getParameter('public_site'),
                'user' => $this->getUser(),
                'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                        
        );
        
        if ($task->getType() == 'HTML validation') {
            $documentationUrls = $this->getHtmlValidationErrorDocumentationUrls($task);
            $fixes = $this->getErrorFixes($task, $documentationUrls);
            
            $viewData['documentation_urls'] = $documentationUrls;
            $viewData['fixes'] = $fixes;
            $viewData['distinct_fixes'] = $this->getDistinctFixes($fixes);
        }        
        
        if ($task->getType() == 'CSS validation') {
            $viewData['errors_by_ref'] = $this->getCssValidationErrorsGroupedByRef($task);
            $viewData['warnings_by_ref'] = $this->getCssValidationWarningsGroupedByRef($task);
        }
        
        if ($task->getType() == 'JS static analysis') {
            $viewData['errors_by_js_context'] = $this->getJsStaticAnalysisErrorsGroupedByContext($task);
        }
        
        return $this->getCachableResponse($this->render(
            'SimplyTestableWebClientBundle:App:task/results.html.twig',
            $viewData
        ), $cacheValidatorHeaders);        
    }
    
    private function getDistinctFixes($fixes) {
        $distinctUrls = array();
        $distinctFixes = array();
        
        foreach ($fixes as $fix) {
            if (!in_array($fix['documentation_url'], $distinctUrls)) {
                $distinctUrls[] = $fix['documentation_url'];
                $distinctFixes[] = $fix;
            }
        }
        
        return $distinctFixes;
    }
    
    private function getErrorFixes(Task $task, $documentationUrls) {
        $fixes = array();
        
        if ($task->getOutput()->getErrorCount() === 0) {
            return $fixes;
        }        
        
        if ($task->getType() != 'HTML validation') {
            return $fixes;
        }
        
        $errors = $task->getOutput()->getResult()->getErrors();
        
        foreach ($errors as $errorIndex => $error) {
            if (isset($documentationUrls[$errorIndex]) && $documentationUrls[$errorIndex]['exists'] === true) {
                $fixes[] = array(
                    'error' => ucfirst($error),
                    'documentation_url' => $documentationUrls[$errorIndex]['url']
                );
            }
        }
        
        return $fixes;        
    }
    
    private function getHtmlValidationErrorDocumentationUrls(Task $task) {
        $documentationUrls = array();
        
        if ($task->getOutput()->getErrorCount() === 0) {
            return $documentationUrls;
        }
        
        $documentationSiteProperties = $this->container->getParameter('documentation_site');
        
        $baseUrl = $documentationSiteProperties['urls']['home']
                 . $documentationSiteProperties['urls']['errors']
                 . $documentationSiteProperties['urls']['html-validation'];
        
        $errors = $task->getOutput()->getResult()->getErrors();
        
        $normaliser = new \webignition\HtmlValidationErrorNormaliser\HtmlValidationErrorNormaliser();        
        $linkifier = new \webignition\HtmlValidationErrorLinkifier\HtmlValidationErrorLinkifier();
        
        $this->getDocumentationUrlCheckerService()->setDocumentationSitemapPath($this->container->get('kernel')->locateResource('@SimplyTestableWebClientBundle/Resources/config/documentation_sitemap.xml'));        
        foreach ($errors as $error) {
            $normalisationResult = $normaliser->normalise($error->getMessage());
            
            if ($normalisationResult->isNormalised()) {
                $parameterisedUrl = $baseUrl . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm()) . '/' . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm(), $normalisationResult->getNormalisedError()->getParameters()) . '/';
                
                if ($this->getDocumentationUrlCheckerService()->exists($parameterisedUrl)) {
                    $documentationUrls[] = array(
                        'url' => $parameterisedUrl,
                        'exists' => true
                    );
                } else {
                    $url = $baseUrl . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm()) . '/';
                    $documentationUrls[] = array(
                        'url' => $url,
                        'exists' => $this->getDocumentationUrlCheckerService()->exists($url)
                    );                                         
                }
            } else {
                $url = $baseUrl . $linkifier->linkify($normalisationResult->getRawError()) . '/';
                $documentationUrls[] = array(
                    'url' => $url,
                    'exists' => $this->getDocumentationUrlCheckerService()->exists($url)
                );
            }
        }
        
        return $documentationUrls;
    }
    
    
    private function getCssValidationErrorsGroupedByRef(Task $task) {
        if ($task->getType() != 'CSS validation') {
            return array();
        }
        
        $errorsGroupedByRef = array();
        $errors = $task->getOutput()->getResult()->getErrors();
        
        foreach ($errors as $error) {
            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage */
            if (!isset($errorsGroupedByRef[$error->getRef()])) {
                $errorsGroupedByRef[$error->getRef()] = array();
            }
            
            $errorsGroupedByRef[$error->getRef()][] = $error;
        }
        
        return $errorsGroupedByRef;
    }
    
    
    private function getCssValidationWarningsGroupedByRef(Task $task) {
        if ($task->getType() != 'CSS validation') {
            return array();
        }
        
        $errorsGroupedByRef = array();
        $errors = $task->getOutput()->getResult()->getWarnings();
        
        foreach ($errors as $error) {
            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage */
            if (!isset($errorsGroupedByRef[$error->getRef()])) {
                $errorsGroupedByRef[$error->getRef()] = array();
            }
            
            $errorsGroupedByRef[$error->getRef()][] = $error;
        }
        
        return $errorsGroupedByRef;        
    }
    
    
    private function getJsStaticAnalysisErrorsGroupedByContext(Task $task) {
        if ($task->getType() != 'JS static analysis') {
            return array();
        }
        
        $errorsGroupedByContext = array();
        $errors = $task->getOutput()->getResult()->getErrors();
        
        foreach ($errors as $error) {
            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage */
            if (!isset($errorsGroupedByContext[$error->getContext()])) {
                $errorsGroupedByContext[$error->getContext()] = array();
            }
            
            $errorsGroupedByContext[$error->getContext()][] = $error;
        }
        
        return $errorsGroupedByContext;
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService 
     */
    protected function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }     
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService 
     */
    protected function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
    }
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService
     */
    protected function getDocumentationUrlCheckerService() {
        return $this->container->get('simplytestable.services.documentationurlcheckerservice');
    }    
    
    
    /**
     *
     * @return \JMS\SerializerBundle\Serializer\Serializer
     */
    protected function getSerializer() {
        return $this->container->get('serializer');
    }    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory
     */
    private function getTaskOutputResultParserService() {
        return $this->container->get('simplytestable.services.taskoutputresultparserfactoryservice');
    }
}
