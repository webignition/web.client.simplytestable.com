<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\ViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\HttpFoundation\Response;

class PreparingStatsController extends ViewController implements RequiresValidUser, RequiresValidOwner {

    public function getInvalidOwnerResponse() {
        return $this->renderUncacheableResponse(array(
            'id' => 0,
            'completion_percent' => 0,
            'remaining_tasks_to_retrieve_count' => 0,
            'local_task_count' => 0,
            'remote_task_count' => 0
        ));
    }

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test',
            'verbose.html.twig'
        ), array(
            ':bs3/Test',
            'index.html.twig'
        ), $viewName);
    }


    public function indexAction($website, $test_id) {
        $remoteTest = $this->getRemoteTest();

        $localTaskCount = $this->getTest()->getTaskCount();
        $completionPercent = round(($localTaskCount / $remoteTest->getTaskCount()) * 100);
        $remainingTasksToRetrieveCount = $remoteTest->getTaskCount() - $localTaskCount;

        return $this->renderUncacheableResponse([
            'id' => $this->getTest()->getTestId(),
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remainingTasksToRetrieveCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTest->getTaskCount()
        ]);
    }

}
