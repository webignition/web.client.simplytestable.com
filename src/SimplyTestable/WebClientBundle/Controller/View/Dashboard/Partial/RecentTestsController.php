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

}