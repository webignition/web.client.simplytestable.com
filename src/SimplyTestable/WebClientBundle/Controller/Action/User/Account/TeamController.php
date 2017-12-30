<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TeamController extends BaseController implements RequiresPrivateUser
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
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    /**
     * @return RedirectResponse
     */
    public function createAction()
    {
        $session = $this->container->get('session');
        $request = $this->container->get('request');
        $teamService = $this->container->get('simplytestable.services.teamservice');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
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

        $teamService->setUser($this->getUser());
        $teamService->create($name);

        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function inviteMemberAction()
    {
        $session = $this->container->get('session');
        $teamInviteService = $this->get('simplytestable.services.teaminviteservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $request = $this->container->get('request');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $invitee = trim($requestData->get('email'));

        $user = $this->getUser();
        $username = $user->getUsername();

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($invitee)) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                self::FLASH_BAG_KEY_INVITEE => $invitee,
            ];

            $session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

            return $redirectResponse;
        }

        if ($invitee === $username) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => self::FLASH_BAG_TEAM_INVITE_GET_ERROR_SELF_INVITE,
            ];

            $session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

            return $redirectResponse;
        }

        $teamInviteService->setUser($user);

        try {
            $invite = $teamInviteService->get($invitee);

            if ($userService->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($invite);
            } else {
                $this->sendInviteActivationEmail($invite);
            }

            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_SUCCESS,
                self::FLASH_BAG_KEY_INVITEE => $invite->getUser(),
                self::FLASH_BAG_KEY_TEAM => $invite->getTeam()
            ];
        } catch (TeamServiceException $teamServiceException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromTeamServiceException($teamServiceException),
                self::FLASH_BAG_KEY_INVITEE => $invitee
            ];
        } catch (PostmarkResponseException $postmarkResponseException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromPostmarkResponseException(
                    $postmarkResponseException
                ),
                self::FLASH_BAG_KEY_INVITEE => $invitee,
            ];

            $invite = new Invite([
                'user' => $invitee
            ]);

            $teamInviteService->removeForUser($invite);
        }

        $session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

        return $redirectResponse;
    }

    /**
     * @param PostmarkResponseException $postmarkResponseException
     *
     * @return string
     */
    private function getFlashErrorCodeFromPostmarkResponseException(
        PostmarkResponseException $postmarkResponseException
    ) {
        if ($postmarkResponseException->isNotAllowedToSendException()) {
            return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND;
        }

        if ($postmarkResponseException->isInactiveRecipientException()) {
            return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT;
        }

        if ($postmarkResponseException->isInvalidEmailAddressException()) {
            return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL;
        }

        return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN;
    }

    /**
     * @param TeamServiceException $teamServiceException
     *
     * @return string
     */
    private function getFlashErrorCodeFromTeamServiceException(TeamServiceException $teamServiceException)
    {
        if ($teamServiceException->isInviteeIsATeamLeaderException()) {
            return self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER;
        }

        if ($teamServiceException->isUserIsAlreadyOnATeamException()) {
            return self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_ALREADY_ON_A_TEAM;
        }

        if ($teamServiceException->isInviteeHasAPremiumPlanException()) {
            return self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_HAS_A_PREMIUM_PLAN;
        }

        return self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_UNKNOWN;
    }

    /**
     * @return RedirectResponse
     */
    public function respondInviteAction()
    {
        $teamInviteService = $this->get('simplytestable.services.teaminviteservice');
        $request = $this->container->get('request');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $response = trim($requestData->get('response'));

        if (in_array($response, ['accept', 'decline'])) {
            $team = trim($requestData->get('team'));
            $user = $this->getUser();
            $username = $user->getUsername();

            $invite = new Invite([
                'user' => $username,
                'team' => $team
            ]);

            if ($response === 'accept') {
                $teamInviteService->acceptInvite($invite);
            } else {
                $teamInviteService->declineInvite($invite);
            }
        }

        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     */
    public function removeInviteAction()
    {
        $request = $this->container->get('request');
        $teamInviteService = $this->get('simplytestable.services.teaminviteservice');

        $requestData = $request->request;

        $invitee = trim($requestData->get('user'));

        $teamInviteService->removeForUser(new Invite([
            'user' => $invitee
        ]));

        return $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function removeMemberAction()
    {
        $request = $this->container->get('request');
        $teamService = $this->container->get('simplytestable.services.teamservice');

        $requestData = $request->request;
        $member = trim($requestData->get('user'));

        $teamService->removeFromTeam($member);

        return $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function resendInviteAction()
    {
        $session = $this->container->get('session');
        $teamInviteService = $this->get('simplytestable.services.teaminviteservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $request = $this->container->get('request');

        $requestData = $request->request;

        $invitee = trim($requestData->get('user'));

        try {
            $invite = $teamInviteService->get($invitee);

            if ($userService->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($invite);
            } else {
                $this->sendInviteActivationEmail($invite);
            }

            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_SUCCESS,
                self::FLASH_BAG_KEY_INVITEE => $invite->getUser(),
                self::FLASH_BAG_KEY_TEAM => $invite->getTeam()
            ];
        } catch (TeamServiceException $teamServiceException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromTeamServiceException($teamServiceException),
                self::FLASH_BAG_KEY_INVITEE => $invitee
            ];
        } catch (PostmarkResponseException $postmarkResponseException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromPostmarkResponseException(
                    $postmarkResponseException
                ),
                self::FLASH_BAG_KEY_INVITEE => $invitee,
            ];
        }

        $session->getFlashBag()->set(self::FLASH_BAG_TEAM_RESEND_INVITE_KEY, $flashData);

        return $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function leaveAction()
    {
        $teamService = $this->container->get('simplytestable.services.teamservice');
        $teamService->leave();

        return $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Invite $invite
     *
     * @throws PostmarkResponseException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    private function sendInviteEmail(Invite $invite)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_team_invite_invitation');

        $viewName = 'SimplyTestableWebClientBundle:Email:user-team-invite-invitation.txt.twig';

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invite->getUser());
        $message->setSubject(str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']));
        $message->setTextMessage($this->renderView($viewName, [
            'team_name' => $invite->getTeam(),
            'account_team_page_url' => $this->generateUrl('view_user_account_team_index_index', [], true)
        ]));

        $mailService->getSender()->send($message);
    }

    /**
     * @param Invite $invite
     *
     * @throws PostmarkResponseException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    private function sendInviteActivationEmail(Invite $invite)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_team_invite_newuser_invitation');

        $confirmationUrl = $this->generateUrl(
            'view_user_signup_invite_index',
            [
                'token' => $invite->getToken()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $viewName = 'SimplyTestableWebClientBundle:Email:user-team-invite-newuser-invitation.txt.twig';

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invite->getUser());
        $message->setSubject(str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']));
        $message->setTextMessage($this->renderView($viewName, [
            'team_name' => $invite->getTeam(),
            'confirmation_url' => $confirmationUrl
        ]));

        $mailService->getSender()->send($message);
    }
}
