<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Partial\FinishedSummary;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;

class IndexController extends CacheableViewController implements RequiresValidUser, RequiresValidOwner
{    
    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }
    
    public function indexAction($website, $test_id) {
        $test = $this->getTestService()->get($website, $test_id);
        
        $viewData = array(
            'test' => array(
                'test' => $test,
                'remote_test' => $this->getTestService()->getRemoteTestService()->get(),
            )
        );

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return array(
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'website' => $this->getRequest()->attributes->get('website')
        );
    }

}
