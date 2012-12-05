<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends BaseViewController
{  
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
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->sendNotFoundResponse();
        }
        
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
            
//            if (in_array($task->getState(), $this->failedStates)) {
//                $task->setState('failed');
//            }
        }

        return new Response($this->getSerializer()->serialize($tasks, 'json'));
    }
    
    
    public function idCollectionAction($website, $test_id) {        
        $this->getTestService()->setUser($this->getUser());
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->sendNotFoundResponse();
        }
        
        $test = $this->getTestService()->get($website, $test_id, $this->getUser());        
        $taskIds = $this->getTaskService()->getRemoteTaskIds($test);

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
        
        if (!$this->getTestService()->has($website, $test_id, $this->getUser())) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        /**
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
         */
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'website' => $website,
            'test_id' => $test_id,
            'task_id' => $task_id
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $test = $this->getTestService()->get($website, $test_id, $this->getUser());
        $task = $this->getTaskService()->get($test, $task_id);
        
        $this->getCssValidationErrorsGroupedByRef($task);
        
        if ($task->getState() == 'completed' || $task->getState() == 'failed') {
            if ($this->getTaskOutputService()->has($test, $task)) {
                $task->setOutput($this->getTaskOutputService()->get($test, $task));
                $this->getTaskOutputService()->setParsedOutput($task);
            }                
        }
        
        $viewData = array(
                'test' => $test,
                'task' => $task,
                'public_site' => $this->container->getParameter('public_site'),
                'user' => $this->getUser(),
                'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),                        
        );
        
        if ($task->getType() == 'CSS validation') {
            $viewData['errors_by_ref'] = $this->getCssValidationErrorsGroupedByRef($task);
        }
        
        if ($task->getType() == 'JS static analysis') {
            $viewData['errors_by_js_context'] = $this->getJsStaticAnalysisErrorsGroupedByContext($task);
        }
        
        return $this->getCachableResponse($this->render(
            'SimplyTestableWebClientBundle:App:task/results.html.twig',
            $viewData
        ), $cacheValidatorHeaders);        
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
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory
     */
    private function getTaskOutputResultParserService() {
        return $this->container->get('simplytestable.services.taskoutputresultparserfactoryservice');
    }
}
