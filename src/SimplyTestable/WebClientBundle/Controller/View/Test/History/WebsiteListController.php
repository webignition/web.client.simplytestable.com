<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\View\Test\ViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class WebsiteListController extends ViewController implements RequiresValidUser {

    public function indexAction() {
        return $this->renderUncacheableResponse($this->getTestService()->getRemoteTestService()->getFinishedWebsites());
    }

}
