<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;

class InviteController extends CacheableViewController implements IEFiltered {
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction() {
        $token = trim($this->getRequest()->attributes->get('token'));

        $viewData = [
            'invite_accept_error' => $this->getFlash('invite_accept_error', true),
            'invite_accept_failure' => $this->getFlash('invite_accept_failure', true),
            'token' => $token,
            'invite' => $this->getTeamInviteService()->getForToken($token),
            'has_invite' => $this->getTeamInviteService()->hasForToken($token)
        ];

        return $this->renderCacheableResponse($viewData);
    }

    
    public function getCacheValidatorParameters() {
        $token = trim($this->getRequest()->attributes->get('token'));

        return array(
            'invite_accept_error' => $this->getFlash('invite_accept_error', false),
            'invite_accept_failure' => $this->getFlash('invite_accept_failure', false),
            'token' => $token,
            'invite' => json_encode($this->getTeamInviteService()->getForToken($token)),
            'has_invite' => json_encode($this->getTeamInviteService()->hasForToken($token))
        );
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamInviteService
     */
    private function getTeamInviteService() {
        return $this->container->get('simplytestable.services.teaminviteservice');
    }

}