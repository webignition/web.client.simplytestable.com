<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TeamInviteController extends AbstractUserAccountController
{
    const FLASH_BAG_KEY_STATUS = 'status';
    const FLASH_BAG_KEY_ERROR = 'error';
    const FLASH_BAG_KEY_INVITEE = 'invitee';
    const FLASH_BAG_KEY_TEAM = 'team';

    const FLASH_BAG_STATUS_ERROR = 'error';
    const FLASH_BAG_STATUS_SUCCESS = 'success';

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
     * @var TeamInviteService
     */
    private $teamInviteService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param TeamInviteService $teamInviteService
     * @param UserManager $userManager
     * @param UserService $userService
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        TeamInviteService $teamInviteService,
        UserManager $userManager,
        UserService $userService,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->teamInviteService = $teamInviteService;
        $this->userManager = $userManager;
        $this->userService = $userService;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function inviteMemberAction(MailService $mailService, Twig_Environment $twig, Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $invitee = trim($requestData->get('email'));

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($invitee)) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => self::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                self::FLASH_BAG_KEY_INVITEE => $invitee,
            ];

            $this->session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

            return $redirectResponse;
        }

        if ($invitee === $username) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => self::FLASH_BAG_TEAM_INVITE_GET_ERROR_SELF_INVITE,
            ];

            $this->session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

            return $redirectResponse;
        }

        try {
            $invite = $this->teamInviteService->get($invitee);

            if ($this->userService->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($mailService, $twig, $invite);
            } else {
                $this->sendInviteActivationEmail($mailService, $twig, $invite);
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

            $this->teamInviteService->removeForUser($invite);
        }

        $this->session->getFlashBag()->set(self::FLASH_BAG_TEAM_INVITE_GET_KEY, $flashData);

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
     * @param Request $request
     *
     * @return RedirectResponse
     */

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws InvalidCredentialsException
     */
    public function respondInviteAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $response = trim($requestData->get('response'));

        if (in_array($response, ['accept', 'decline'])) {
            $team = trim($requestData->get('team'));
            $user = $this->userManager->getUser();
            $username = $user->getUsername();

            $invite = new Invite([
                'user' => $username,
                'team' => $team
            ]);

            if ($response === 'accept') {
                $this->teamInviteService->acceptInvite($invite);
            } else {
                $this->teamInviteService->declineInvite($invite);
            }
        }

        return $redirectResponse;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws InvalidCredentialsException
     */
    public function removeInviteAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $invitee = trim($requestData->get('user'));

        $this->teamInviteService->removeForUser(new Invite([
            'user' => $invitee
        ]));

        return new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function resendInviteAction(MailService $mailService, Twig_Environment $twig, Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $invitee = trim($requestData->get('user'));

        try {
            $invite = $this->teamInviteService->get($invitee);

            if ($this->userService->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($mailService, $twig, $invite);
            } else {
                $this->sendInviteActivationEmail($mailService, $twig, $invite);
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

        $this->session->getFlashBag()->set(self::FLASH_BAG_TEAM_RESEND_INVITE_KEY, $flashData);

        return new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    private function sendInviteEmail(MailService $mailService, Twig_Environment $twig, Invite $invite)
    {
        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_team_invite_invitation');

        $viewName = 'SimplyTestableWebClientBundle:Email:user-team-invite-invitation.txt.twig';

        $message = $mailService->getNewMessage();

        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invite->getUser());
        $message->setSubject(str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']));
        $message->setTextMessage($twig->render($viewName, [
            'team_name' => $invite->getTeam(),
            'account_team_page_url' => $this->router->generate(
                'view_user_account_team_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        ]));

        $mailService->getSender()->send($message);
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendInviteActivationEmail(MailService $mailService, Twig_Environment $twig, Invite $invite)
    {
        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_team_invite_newuser_invitation');

        $confirmationUrl = $this->router->generate(
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
        $message->setTextMessage($twig->render($viewName, [
            'team_name' => $invite->getTeam(),
            'confirmation_url' => $confirmationUrl
        ]));

        $mailService->getSender()->send($message);
    }
}
