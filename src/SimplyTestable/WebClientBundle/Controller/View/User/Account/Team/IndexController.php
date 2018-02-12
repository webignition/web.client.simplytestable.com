<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered
{
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_team_index_index']))
        ], true));
    }

    /**
     * @return Response
     *
     * @throws WebResourceException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $session = $this->get('session');
        $teamService = $this->container->get('simplytestable.services.teamservice');
        $teamInviteService = $this->container->get('simplytestable.services.teaminviteservice');
        $templating = $this->container->get('templating');

        $user = $userService->getUser();
        $userSummary = $userService->getSummary();
        $flashBag = $session->getFlashBag();
        $teamSummary = $userSummary->getTeamSummary();

        $teamCreateErrorValues = $flashBag->get(TeamController::FLASH_BAG_CREATE_ERROR_KEY);
        $teamCreateError = empty($teamCreateErrorValues) ? '' : $teamCreateErrorValues[0];

        $viewData = [
            'plan_presentation_name' => ucwords(
                $userSummary->getPlan()->getAccountPlan()->getName()
            ),
            'user_summary' => $userSummary,
            'team_create_error' => $teamCreateError,
        ];

        $teamInviteGetData = $flashBag->get(TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY);
        if (is_array($teamInviteGetData) && count($teamInviteGetData)) {
            $viewData['team_invite_get'] = $teamInviteGetData;
        }

        $teamInviteResendData = $flashBag->get(TeamController::FLASH_BAG_TEAM_RESEND_INVITE_KEY);
        if (is_array($teamInviteResendData) && count($teamInviteResendData)) {
            $viewData['team_invite_resend'] = $teamInviteResendData;
        }

        if ($teamSummary->isInTeam()) {
            $team = $teamService->getTeam();
            $viewData['team'] = $team;

            if ($team->getLeader() === $user->getUsername()) {
                $viewData['invites'] = $teamInviteService->getForTeam();
            }
        }

        if ($teamSummary->hasInvite()) {
            $viewData['invites'] = $teamInviteService->getForUser();
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/Account/Team/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        return new Response($content);
    }
}
