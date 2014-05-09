<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;

abstract class ViewController extends BaseViewController {


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    protected function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }

}
