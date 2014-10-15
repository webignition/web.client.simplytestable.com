<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\HttpFoundation\Response;

class ByTaskTypeController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }


    public function indexAction($website, $test_id, $task_type) {
        if ($this->getTest()->getState() == 'failed-no-sitemap') {
            return $this->issueRedirect($this->generateUrl('view_test_results_failednourlsdetected_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if ($this->getTest()->getState() == 'rejected') {
            return $this->issueRedirect($this->generateUrl('view_test_results_rejected_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if (!$this->getTestService()->isFinished($this->getTest())) {
            return $this->issueRedirect($this->generateUrl('view_test_progress_index_index', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        $task_type = str_replace('+', ' ', $task_type);

        if (!$this->isTaskTypeSelected($task_type)) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if ($this->getTest()->getWebsite() != $website) {
            return $this->issueRedirect($this->generateUrl('view_test_results_bytasktype_index', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id,
                'task_type' => $task_type
            ), true));
        }

        $isOwner = $this->getTestService()->getRemoteTestService()->owns($this->getTest());

        $viewData = [
            'is_owner' => $isOwner,
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'task_type' => $this->getSelectedTaskType($task_type)
        ];

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return [];
    }


    /**
     * @param string $taskType
     * @return bool
     */
    private function isTaskTypeSelected($taskType) {
        return !is_null($this->getSelectedTaskType($taskType));
    }


    /**
     * @param string $taskType
     * @return null
     */
    private function getSelectedTaskType($taskType) {
        $remoteTaskTypes = $this->getRemoteTest()->getTaskTypes();

        foreach ($remoteTaskTypes as $remoteTaskType) {
            if (strtolower($remoteTaskType) == strtolower($taskType)) {
                return $remoteTaskType;
            }
        }

        return null;
    }

}
