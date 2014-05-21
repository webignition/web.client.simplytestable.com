<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {


    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test',
        ), array(
            ':bs3/Test',
        ), $viewName);
    }


    public function indexAction($website, $test_id) {
        $test = $this->getTestService()->get($website, $test_id);
        $isOwner = $this->getTestService()->getRemoteTestService()->owns($test);

        $viewData = array(
            'website' => $this->getUrlViewValues($website),
            'test' => $test,
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'remote_test' => $this->getRemoteTest(),
            'is_owner' => $isOwner
        );

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return array(
            'rand' => rand()
        );
    }


    /**
     * @return bool|\SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest
     */
    private function getRemoteTest() {
        return $this->getTestService()->getRemoteTestService()->get();
    }

}
