<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser {
    
    protected function modifyViewName($viewName) {
        return str_replace(
            ':Dashboard',
            ':bs3/Dashboard',
            $viewName
        );
    }
    
    public function indexAction() {
        $viewData = [];

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {        
        return array(
            'rand' => rand()
        );
    }

}