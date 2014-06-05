<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Task\TaskList;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }


    public function getInvalidOwnerResponse() {
        return new Response('', 400);
    }


    public function getCacheValidatorParameters() {
        return array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'task_collection_hash' => $this->getTaskCollectionHash()
        );
    }
    
    
    public function indexAction($website, $test_id) {
        if (!$this->hasRequestTaskIds()) {
            return new Response('');
        }

        $viewData = array(
            'test' => $this->getTest(),
            'tasks' => $this->getTasks()
        );

        return $this->renderCacheableResponse($viewData);
    }


    /**
     * @return int[]
     */
    private function getRequestTaskIds() {
        $taskIds = $this->getRequest()->get('taskIds');
        if (!is_array($taskIds)) {
            return array();
        }

        foreach ($taskIds as $key => $value) {
            if (ctype_digit($value)) {
                $taskIds[$key] = (int)$value;
            } else {
                unset($taskIds[$key]);
            }
        }

        return $taskIds;
    }


    /**
     * @return bool
     */
    private function hasRequestTaskIds() {
        return count($this->getRequestTaskIds()) > 0;
    }


    /**
     * @return Task[]
     */
    private function getTasks() {
        if (!$this->hasRequestTaskIds()) {
            return array();
        }

        return $this->getTaskService()->getCollection($this->getTest(), $this->getRequestTaskIds());
    }


    /**
     * @return string
     */
    private function getTaskCollectionHash() {
        $hashableContent = '';

        foreach ($this->getTasks() as $task) {
            $hashableContent .= $task->getTaskId() . ':' . $task->getState() . '::';
        }

        return md5($hashableContent);
    }
}