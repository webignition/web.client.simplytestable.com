<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Preparing;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }
    
    
    public function indexAction($website, $test_id) {
        if (!$this->getTestService()->isFinished($this->getTest())) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }

        if ($this->getTest()->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        if (!$this->getTest()->hasTaskIds()) {
            $this->getTaskService()->getRemoteTaskIds($this->getTest());
        }

        return $this->renderCacheableResponse(array(
            'completion_percent' => $this->getCompletionPercent($this->getTest()->getTaskCount()),
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'local_task_count' => $this->getTest()->getTaskCount(),
            'remote_task_count' => $this->getRemoteTest()->getTaskCount(),
            'remaining_tasks_to_retrieve_count' => $this->getRemainingTasksToRetrieveCount($this->getTest()->getTaskCount())
        ));
    }

    public function getCacheValidatorParameters() {
        $test = $this->getTest();

        return array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'completion_percent' => $this->getCompletionPercent($test->getTaskCount()),
            'remaining_tasks_to_retrieve_count' => $this->getRemainingTasksToRetrieveCount($test->getTaskCount())
        );
    }

    /**
     * @param $localTaskCount int
     * @return int
     */
    private function getCompletionPercent($localTaskCount) {
        return (int)round(($localTaskCount / $this->getRemoteTest()->getTaskCount()) * 100);
    }


    /**
     * @param $localTaskCount int
     * @return int
     */
    private function getRemainingTasksToRetrieveCount($localTaskCount) {
        return $this->getRemoteTest()->getTaskCount() - $localTaskCount;
    }

}
