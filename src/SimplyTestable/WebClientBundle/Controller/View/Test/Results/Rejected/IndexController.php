<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Rejected;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }
    
    
    public function indexAction($website, $test_id) {
        if ($this->getTest()->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        if ($this->getTest()->getState() !== 'rejected') {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }

        $viewData = array(
            'website' => $this->getUrlViewValues($website),
            'remote_test' => $this->getTestService()->getRemoteTestService()->get(),
            'plans' => $this->container->getParameter('plans')
        );

        if ($this->isRejectedDueToCreditLimit()) {
            $viewData['userSummary'] = $this->getUserService()->getSummary();
        }

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        $cacheValidatorParameters = array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id')
        );

        $this->getTestService()->get(
            $this->getRequest()->attributes->get('website'),
            $this->getRequest()->attributes->get('test_id')
        );

        $remoteTest = $this->getTestService()->getRemoteTestService()->get();

        if ($this->isRejectedDueToCreditLimit()) {
            $userSummary = $this->getUserService()->getSummary();
            $cacheValidatorParameters['limits'] = $remoteTest->getRejection()->getConstraint()->limit . ':' . $userSummary->getPlanConstraints()->credits->limit;
            $cacheValidatorParameters['credits_remaining'] = $userSummary->getPlanConstraints()->credits->limit -$userSummary->getPlanConstraints()->credits->used;
        }

        return $cacheValidatorParameters;
    }


    /**
     * @return bool
     */
    private function isRejectedDueToCreditLimit() {
        if ($this->getRemoteTest()->getRejection()->getReason() != 'plan-constraint-limit-reached') {
            return false;
        }

        return $this->getRemoteTest()->getRejection()->getConstraint()->name == 'credits_per_month';
    }
}
