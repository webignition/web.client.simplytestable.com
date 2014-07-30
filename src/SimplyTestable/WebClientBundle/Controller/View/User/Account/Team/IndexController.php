<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }


    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUserSignInRedirectResponse() {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_team_index_index']))
        ], true));
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

        $teamInviteResendData = $this->get('session')->getFlashBag()->get('team_invite_resend');
        if (is_array($teamInviteResendData) && count($teamInviteResendData)) {
            $viewData['team_invite_resend'] = $teamInviteResendData;
        }

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $team = $this->getTeamService()->getTeam();
            $viewData['team'] = $team;

            if ($team->getLeader() == $this->getUser()->getUsername()) {
                $viewData['invites'] = $this->getTeamInviteService()->getForTeam();
            }
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