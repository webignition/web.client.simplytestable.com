<?php

namespace App\Controller\Action\User\Account;

use Egulias\EmailValidator\Validation\RFCValidation;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Exception\Team\Service\Exception as TeamServiceException;
use App\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use App\Services\Configuration\MailConfiguration;
use App\Services\TeamInviteService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @var UserService
     */
    private $userService;

    /**
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param TeamInviteService $teamInviteService
     * @param UserService $userService
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        TeamInviteService $teamInviteService,
        UserService $userService
    ) {
        parent::__construct($router, $userManager, $session);

        $this->teamInviteService = $teamInviteService;
        $this->userService = $userService;
    }

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
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
    public function inviteMemberAction(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        Request $request
    ) {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($this->generateUrl('view_user_account_team_index_index'));

        $invitee = trim($requestData->get('email'));

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($invitee, new RFCValidation())) {
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
                $this->sendInviteEmail($mailConfiguration, $postmarkClient, $twig, $invite);
            } else {
                $this->sendInviteActivationEmail($mailConfiguration, $postmarkClient, $twig, $invite);
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
        } catch (PostmarkException $postmarkException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromPostmarkResponseException($postmarkException),
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
     * @param PostmarkException $postmarkException
     *
     * @return string
     */
    private function getFlashErrorCodeFromPostmarkResponseException(PostmarkException $postmarkException)
    {
        if (405 === $postmarkException->postmarkApiErrorCode) {
            return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND;
        }

        if (406 === $postmarkException->postmarkApiErrorCode) {
            return self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT;
        }

        if (300 === $postmarkException->postmarkApiErrorCode) {
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

        $redirectResponse = new RedirectResponse($this->generateUrl('view_user_account_team_index_index'));

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

        return new RedirectResponse($this->generateUrl('view_user_account_team_index_index'));
    }

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
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
    public function resendInviteAction(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        Request $request
    ) {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $invitee = trim($requestData->get('user'));

        try {
            $invite = $this->teamInviteService->get($invitee);

            if ($this->userService->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($mailConfiguration, $postmarkClient, $twig, $invite);
            } else {
                $this->sendInviteActivationEmail($mailConfiguration, $postmarkClient, $twig, $invite);
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
        } catch (PostmarkException $postmarkException) {
            $flashData = [
                self::FLASH_BAG_KEY_STATUS => self::FLASH_BAG_STATUS_ERROR,
                self::FLASH_BAG_KEY_ERROR => $this->getFlashErrorCodeFromPostmarkResponseException($postmarkException),
                self::FLASH_BAG_KEY_INVITEE => $invitee,
            ];
        }

        $this->session->getFlashBag()->set(self::FLASH_BAG_TEAM_RESEND_INVITE_KEY, $flashData);

        return new RedirectResponse($this->generateUrl('view_user_account_team_index_index'));
    }

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient,
     * @param Twig_Environment $twig
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    private function sendInviteEmail(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        Invite $invite
    ) {
        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_team_invite_invitation');

        $confirmationUrl = $this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $postmarkClient->sendEmail(
            $sender['email'],
            $invite->getUser(),
            str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']),
            null,
            $twig->render(
                'Email/user-team-invite-invitation.txt.twig',
                [
                    'team_name' => $invite->getTeam(),
                    'account_team_page_url' => $confirmationUrl
                ]
            )
        );
    }

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     * @param Twig_Environment $twig
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendInviteActivationEmail(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        Invite $invite
    ) {
        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_team_invite_newuser_invitation');

        $confirmationUrl = $this->generateUrl(
            'view_user_signup_invite_index',
            [
                'token' => $invite->getToken()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $postmarkClient->sendEmail(
            $sender['email'],
            $invite->getUser(),
            str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']),
            null,
            $twig->render(
                'Email/user-team-invite-newuser-invitation.txt.twig',
                [
                    'team_name' => $invite->getTeam(),
                    'confirmation_url' => $confirmationUrl
                ]
            )
        );
    }
}
