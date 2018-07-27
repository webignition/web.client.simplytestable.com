<?php

namespace App\Services;

use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Model\Team\Invite;
use App\Services\Configuration\MailConfiguration;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Mailer
{
    const VIEW_SIGN_UP_CONFIRMATION = 'Email/user-creation-confirmation.txt.twig';
    const VIEW_EMAIL_CHANGE_CONFIRMATION = 'Email/user-email-change-request-confirmation.txt.twig';
    const VIEW_TEAM_INVITE_EXISTING_USER = 'Email/user-team-invite-invitation.txt.twig';
    const VIEW_TEAM_INVITE_NEW_USER = 'Email/user-team-invite-newuser-invitation.txt.twig';
    const VIEW_RESET_PASSWORD_CONFIRMATION = 'Email/reset-password-confirmation.txt.twig';

    /**
     * @var MailConfiguration
     */
    private $mailConfiguration;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PostmarkClient
     */
    private $postmarkClient;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(
        MailConfiguration $mailConfiguration,
        RouterInterface $router,
        PostmarkClient $postmarkClient,
        \Twig_Environment $twig
    ) {
        $this->mailConfiguration = $mailConfiguration;
        $this->router = $router;
        $this->postmarkClient = $postmarkClient;
        $this->twig = $twig;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function sendSignUpConfirmationToken(string $email, string $token)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl('view_user_sign_up_confirm', [
            'email' => $email,
            'token' => $token,
        ]);

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $email,
            $messageProperties['subject'],
            null,
            $this->twig->render(
                self::VIEW_SIGN_UP_CONFIRMATION,
                [
                    'confirmation_url' => $confirmationUrl,
                    'confirmation_code' => $token
                ]
            )
        );
    }

    /**
     * @param array $details
     *
     * @throws PostmarkException
     */
    public function sendInvalidAdminCredentialsNotification(array $details)
    {
        $sender = $this->mailConfiguration->getSender('default');

        $this->postmarkClient->sendEmail(
            $sender['email'],
            'jon@simplytestable.com',
            'Invalid admin user credentials',
            null,
            json_encode($details)
        );
    }

    /**
     * @param string $newEmail
     * @param string $currentEmail
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function sendEmailChangeConfirmationToken(string $newEmail, string $currentEmail, string $token)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_email_change_request_confirmation');

        $confirmationUrl = $this->generateUrl('view_user_account', [
            'token' => $token,
        ]);

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $newEmail,
            $messageProperties['subject'],
            null,
            $this->twig->render(
                self::VIEW_EMAIL_CHANGE_CONFIRMATION,
                [
                    'current_email' => $currentEmail,
                    'new_email' => $newEmail,
                    'confirmation_url' => $confirmationUrl,
                    'confirmation_code' => $token,
                ]
            )
        );
    }

    /**
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function sendTeamInviteForExistingUser(Invite $invite)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_team_invite_invitation');

        $confirmationUrl = $this->generateUrl('view_user_account_team');

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $invite->getUser(),
            str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']),
            null,
            $this->twig->render(
                self::VIEW_TEAM_INVITE_EXISTING_USER,
                [
                    'team_name' => $invite->getTeam(),
                    'account_team_page_url' => $confirmationUrl
                ]
            )
        );
    }

    /**
     * @param Invite $invite
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function sendTeamInviteForNewUser(Invite $invite)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_team_invite_newuser_invitation');

        $confirmationUrl = $this->generateUrl('view_user_sign_up_invite', [
            'token' => $invite->getToken()
        ]);

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $invite->getUser(),
            str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']),
            null,
            $this->twig->render(
                self::VIEW_TEAM_INVITE_NEW_USER,
                [
                    'team_name' => $invite->getTeam(),
                    'confirmation_url' => $confirmationUrl
                ]
            )
        );
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @throws PostmarkException
     * @throws MailConfigurationException
     */
    public function sendPasswordResetConfirmationToken($email, $token)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_reset_password');

        $confirmationUrl = $this->generateUrl('view_user_reset_password_choose', [
            'email' => $email,
            'token' => $token
        ]);

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $email,
            $messageProperties['subject'],
            null,
            $this->twig->render(
                self::VIEW_RESET_PASSWORD_CONFIRMATION,
                [
                    'confirmation_url' => $confirmationUrl,
                    'email' => $email
                ]
            )
        );
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return string
     */
    private function generateUrl(string $routeName, array $routeParameters = [])
    {
        return $this->router->generate($routeName, $routeParameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
