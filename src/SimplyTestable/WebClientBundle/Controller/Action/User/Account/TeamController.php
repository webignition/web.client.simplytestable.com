<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\TeamService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TeamController extends AbstractUserAccountController
{
    const FLASH_BAG_KEY_STATUS = 'status';
    const FLASH_BAG_KEY_ERROR = 'error';
    const FLASH_BAG_KEY_INVITEE = 'invitee';
    const FLASH_BAG_KEY_TEAM = 'team';

    const FLASH_BAG_STATUS_ERROR = 'error';
    const FLASH_BAG_STATUS_SUCCESS = 'success';

    const FLASH_BAG_CREATE_ERROR_KEY = 'team_create_error';
    const FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK = 'blank-name';

    const FLASH_BAG_TEAM_INVITE_GET_KEY = 'team_invite_get';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID = 'invalid-invitee';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_SELF_INVITE = 'invite-self';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER = 'invitee-is-a-team-leader';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_ALREADY_ON_A_TEAM = 'invitee-is-already-on-a-team';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_HAS_A_PREMIUM_PLAN = 'invitee-has-a-premium-plan';
    const FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_UNKNOWN = 'invitee-unknown-error';

    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN = 'postmark-failure';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL = 'invalid-email';

    const FLASH_BAG_TEAM_RESEND_INVITE_KEY = 'team_invite_resend';

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function createAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $session = $this->container->get('session');
        $teamService = $this->container->get(TeamService::class);
        $router = $this->container->get('router');

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $name = trim($requestData->get('name'));

        if (empty($name)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CREATE_ERROR_KEY,
                self::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK
            );

            return $redirectResponse;
        }

        $teamService->create($name);

        return $redirectResponse;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function removeMemberAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $teamService = $this->container->get(TeamService::class);
        $router = $this->container->get('router');

        $requestData = $request->request;
        $member = trim($requestData->get('user'));

        $teamService->removeFromTeam($member);

        return new RedirectResponse($router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function leaveAction()
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $teamService = $this->container->get(TeamService::class);
        $router = $this->container->get('router');

        $teamService->leave();

        return new RedirectResponse($router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
