<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends BaseViewController
{
    public function resultsCollectionAction($website, $test_id, $task_ids) {         
        if (!$this->getTestService()->has($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        } 

        $test = $this->getTestService()->get($website, $test_id);
        $tasks = $this->getTaskService()->getCollection($test, explode(',', $task_ids));
        $output = array();
        
        foreach ($tasks as $task) {
            if ($task->getState() == 'completed') {
                if ($this->getTaskOutputService()->has($test, $task)) {
                    $task->setOutput($this->getTaskOutputService()->get($test, $task));
                    $this->getTaskOutputService()->setParsedOutput($task);                    
                    $output[$task->getId()] = $task->getOutput();
                }                
            }
        }
    
        return new Response($this->getSerializer()->serialize($output, 'json'));        
    }
    
    
    public function resultsAction($website, $test_id, $task_id) {        
        if (!$this->getTestService()->has($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);
        $cacheValidatorIdentifier->setParameter('task_id', $task_id);
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $test = $this->getTestService()->get($website, $test_id);
        $task = $this->getTaskService()->get($test, $task_id);
        
        if ($task->getState() == 'completed') {
            if ($this->getTaskOutputService()->has($test, $task)) {
                $task->setOutput($this->getTaskOutputService()->get($test, $task));
                $this->getTaskOutputService()->setParsedOutput($task);
            }                
        }       
        
        return $this->getCachableResponse(
            $this->render(
                    'SimplyTestableWebClientBundle:App:task/results.html.twig',
                    array(
                        'test' => $test,
                        'task' => $task,
                        'public_site' => $this->container->getParameter('public_site')
                    )),
            $cacheValidatorHeaders
        );        
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
}
