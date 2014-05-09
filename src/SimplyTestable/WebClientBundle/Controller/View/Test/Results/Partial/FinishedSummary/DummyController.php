<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Partial\FinishedSummary;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;

class DummyController extends CacheableViewController {
    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test',
            'Dummy',
            'dummy'
        ), array(
            ':bs3/Test',
            'Index',
            'index'
        ), $viewName);
    }
    
    public function indexAction() {
        $viewData = array(
            'test' => array(
                'test' => array(
                    'state' => 'foo',
                    'testId' => 0,
                    'website' => 'http://example.com',
                    'formattedWebsite' => 'http://example.com',
                    'formattedWebsite' => 'http://example.com',
                    'urlcount' => 0,
                    'taskCount' => 0,
                    'errorCount' => 0,
                    'warningCount' => 0
                ),
                'remote_test' => array(),
            )
        );

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return array(
            'rand' => rand()
        );
    }

}
