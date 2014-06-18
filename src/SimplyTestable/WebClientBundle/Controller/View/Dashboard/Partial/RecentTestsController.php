<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class RecentTestsController extends BaseViewController implements RequiresValidUser {

    /**
     * @var \SimplyTestable\WebClientBundle\Services\TestService
     */
    private $testService;

    protected function modifyViewName($viewName) {
        return str_replace(
            ':Dashboard',
            ':bs3/Dashboard',
            $viewName
        );
    }
    
    public function indexAction() {
        return $this->renderCacheableResponse([
            'test_list' => $this->getRecentActivity()
        ]);
    }

    private function getRecentActivity() {
        $testList = $this->getTestService()->getRemoteTestService()->getRecent(3);

        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];

            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);

            $testList->addTest($test);

            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->getTaskService()->getCollection($test);
                }
            }
        }

        return $testList;
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
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        if (is_null($this->testService)) {
            $this->testService = $this->container->get('simplytestable.services.testservice');
            $this->testService->getRemoteTestService()->setUser($this->getUserService()->getUser());
        }

        return $this->testService;
    }

}