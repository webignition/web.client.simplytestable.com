<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestHistoryController extends TestViewController
{ 
    const RESULTS_PREPARATION_THRESHOLD = 10;
    const DEFAULT_PAGE_NUMBER = 1;
    
    public function websitesAction() {
        $this->getUserService()->setUser($this->getUser());
        
        $list = ($this->isUserValid()) ? $this->getTestService()->getRemoteTestService()->getFinishedWebsites() : array();
        return $this->getUncacheableResponse(new Response($this->getSerializer()->serialize($list, 'json')));     
    }

}
