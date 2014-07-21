<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    public function indexAction() {
        $this->getUserService()->setUser($this->getUser());
        $userSummary = $this->getUserService()->getSummary();

        $viewData = [
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'user_summary' => $userSummary,
            'team_create_error' => $this->getFlash('team_create_error', true),
        ];

        $teamInviteGetData = $this->get('session')->getFlashBag()->get('team_invite_get');
        if (is_array($teamInviteGetData) && count($teamInviteGetData)) {
            $viewData['team_invite_get'] = $teamInviteGetData;
        }

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $viewData['team'] = $this->getTeamService()->getTeam();
        }

        if ($userSummary->getTeamSummary()->hasInvite()) {
            $viewData['invites'] = $this->getTeaminviteService()->getForUser();
        }

        return $this->renderResponse($this->getRequest(), $viewData);
    }


    /**
     *
     * @param string $plan
     * @return string
     */
    private function getPlanPresentationName($plan) {
        return ucwords($plan);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TeamInviteService
     */
    private function getTeamInviteService() {
        return $this->container->get('simplytestable.services.teaminviteservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->container->get('simplytestable.services.teamservice');
    }

}