<?php

namespace App\Services;

use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Services\Configuration\MailConfiguration;
use Postmark\PostmarkClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Mailer
{
    const VIEW_SIGN_UP_CONFIRMATION = 'Email/user-creation-confirmation.txt.twig';

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
    public function sendSignUpConfirmationToken($email, $token)
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
