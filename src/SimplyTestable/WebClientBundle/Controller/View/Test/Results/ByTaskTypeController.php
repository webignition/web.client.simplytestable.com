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
        $this->getTest();

        // check state
        // requires valid task type
        if (!$this->isTaskTypeSelected($task_type)) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }


        $isOwner = $this->getTestService()->getRemoteTestService()->owns($this->getTest());

        $viewData = [
            'is_owner' => $isOwner,
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
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
        $remoteTaskTypes = $this->getRemoteTest()->getTaskTypes();

        foreach ($remoteTaskTypes as $remoteTaskType) {
            if (strtolower($remoteTaskType) == strtolower($taskType)) {
                return true;
            }
        }

        return false;
    }

}
