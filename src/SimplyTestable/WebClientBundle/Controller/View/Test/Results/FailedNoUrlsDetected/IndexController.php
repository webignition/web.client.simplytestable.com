<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\FailedNoUrlsDetected;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }
    
    
    public function indexAction($website, $test_id) {
        $test = $this->getTestService()->get($website, $test_id);

        if ($test->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $test->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        if ($test->getState() !== 'failed-no-sitemap') {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }

        if (!$this->getUserService()->isPublicUser($this->getUser())) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }

        return $this->renderCacheableResponse(array(
            'website' => $this->getUrlViewValues($website),
            'redirect' => base64_encode(json_encode($this->getRedirectParameters()))
        ));
    }

    private function getRedirectParameters() {
        return array(
            'route' => 'view_test_progress_index_index',
            'parameters' => array(
                'website' => $this->getRequest()->attributes->get('website'),
                'test_id' => $this->getRequest()->attributes->get('test_id')
            )
        );
    }

    public function getCacheValidatorParameters() {
        return array(
            'website' => $this->getRequest()->attributes->get('website'),
            'redirect' => base64_encode(json_encode($this->getRedirectParameters()))
        );
    }
}
