<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;

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
   
    public function collectionAction($website, $test_id) {                
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());

        try {
            if (!$this->getTestService()->has($website, $test_id)) {
                return $this->sendNotFoundResponse(); 
            }            
            
            $test = $this->getTestService()->get($website, $test_id); 
            if (!$this->getTestService()->getRemoteTestService()->authenticate()) {           
                return $this->sendNotFoundResponse(); 
            }
            
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
           
        } catch (WebResourceException $webResourceException) {            
            if ($webResourceException->getCode() == 403) {
                return $this->sendNotFoundResponse();
            }
            
            return new Response($this->getSerializer()->serialize(null, 'json'));
        } catch (\Guzzle\Http\Exception\RequestException $requestException)  {         
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }      
    }
    
    
    public function idCollectionAction($website, $test_id) {                
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        try {
            if (!$this->getTestService()->has($website, $test_id)) {
                return $this->sendNotFoundResponse(); 
            }            
            
            $test = $this->getTestService()->get($website, $test_id); 
            if (!$this->getTestService()->getRemoteTestService()->authenticate()) {           
                return $this->sendNotFoundResponse(); 
            }
            
            $taskIds = $this->getTaskService()->getRemoteTaskIds($test);

            return new Response($this->getSerializer()->serialize($taskIds, 'json'));
           
        } catch (WebResourceException $webResourceException) {            
            if ($webResourceException->getCode() == 403) {
                return $this->sendNotFoundResponse();
            }
            
            return new Response($this->getSerializer()->serialize(null, 'json'));
        } catch (\Guzzle\Http\Exception\RequestException $requestException)  {         
            return new Response($this->getSerializer()->serialize(null, 'json'));
        }
    }    
    
    
    public function unretrievedIdCollectionAction($website, $test_id, $limit = null) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
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
