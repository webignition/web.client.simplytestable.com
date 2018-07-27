<?php

namespace App\Services;

use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Services\Configuration\MailConfiguration;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Mailer
{
    const VIEW_SIGN_UP_CONFIRMATION = 'Email/user-creation-confirmation.txt.twig';
    const VIEW_EMAIL_CHANGE_CONFIRMATION = 'Email/user-email-change-request-confirmation.txt.twig';

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
