<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\DashboardController;

class RecentTestsController extends DashboardController {

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

    public function getCacheValidatorParameters() {        
        return array(
            'rand' => rand()
        );
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
                } else {
//                    if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) - $test->getTaskCount()) {
//                        $this->getTaskService()->getCollection($test);
//                    }
                }
            }
        }

        return $testList;
    }

}