<?php

namespace App\Tests\Functional\Services;

use App\Services\Configuration\MailConfiguration;
use App\Services\Mailer;
use App\Tests\Factory\MockFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Mockery\MockInterface;
use Postmark\PostmarkClient;
use Symfony\Component\Routing\RouterInterface;

class MailerTest extends AbstractBaseTestCase
{
    public function testSendSignUpConfirmationToken()
    {
        $email = 'user@example.com';
        $token = 'token-value';

        $mockRenderedMessage = 'Mock rendered message';

        $twig = MockFactory::createTwig([
            'render' => [
                'withArgs' => function ($viewName, $parameters) use ($token) {
                    $this->assertEquals(Mailer::VIEW_SIGN_UP_CONFIRMATION, $viewName);
                    $this->assertEquals(
                        [
                            'confirmation_url' => 'http://localhost/signup/confirm/user@example.com/?token=' . $token,
                            'confirmation_code' => $token,
                        ],
                        $parameters
                    );

                    return true;
                },
                'return' => $mockRenderedMessage,
            ],
        ]);

        /* @var PostmarkClient|MockInterface $postmarkClient */
        $postmarkClient = \Mockery::mock(PostmarkClient::class);
        $postmarkClient
            ->shouldReceive('sendEmail')
            ->withArgs(function ($from, $to, $subject, $htmlBody, $textBody) use ($email, $mockRenderedMessage) {
                $this->assertEquals('robot@simplytestable.com', $from);
                $this->assertEquals($email, $to);
                $this->assertEquals('[Simply Testable] Activate your account', $subject);
                $this->assertNull($htmlBody);
                $this->assertEquals($mockRenderedMessage, $textBody);

                return true;
            });

        $mailer = new Mailer(
            self::$container->get(MailConfiguration::class),
            self::$container->get(RouterInterface::class),
            $postmarkClient,
            $twig
        );

        $mailer->sendSignUpConfirmationToken($email, $token);
    }

    public function testSendInvalidAdminCredentialsNotification()
    {
        /* @var PostmarkClient|MockInterface $postmarkClient */
        $postmarkClient = \Mockery::mock(PostmarkClient::class);
        $postmarkClient
            ->shouldReceive('sendEmail')
            ->withArgs(function ($from, $to, $subject, $htmlBody, $textBody) {
                $this->assertEquals('robot@simplytestable.com', $from);
                $this->assertEquals('jon@simplytestable.com', $to);
                $this->assertEquals('Invalid admin user credentials', $subject);
                $this->assertNull($htmlBody);
                $this->assertEquals('{"call":"FooService::bar()","args":{"foo":"bar"}}', $textBody);

                return true;
            });

        $mailer = new Mailer(
            self::$container->get(MailConfiguration::class),
            self::$container->get(RouterInterface::class),
            $postmarkClient,
            MockFactory::createTwig()
        );

        $mailer->sendInvalidAdminCredentialsNotification([
            'call' => 'FooService::bar()',
            'args' => [
                'foo' => 'bar',
            ],
        ]);
    }

    public function testSendEmailChangeConfirmationToken()
    {
        $newEmail = 'new-user@example.com';
        $currentEmail = 'user@exaple.com';
        $token = 'token-value-here';

        $mockRenderedMessage = 'Mock rendered message';

        $twig = MockFactory::createTwig([
            'render' => [
                'withArgs' => function ($viewName, $parameters) use ($currentEmail, $newEmail, $token) {
                    $this->assertEquals(Mailer::VIEW_EMAIL_CHANGE_CONFIRMATION, $viewName);
                    $this->assertEquals(
                        [
                            'confirmation_url' => 'http://localhost/account/?token=' . $token,
                            'confirmation_code' => $token,
                            'current_email' => $currentEmail,
                            'new_email' => $newEmail,
                        ],
                        $parameters
                    );

                    return true;
                },
                'return' => $mockRenderedMessage,
            ],
        ]);

        /* @var PostmarkClient|MockInterface $postmarkClient */
        $postmarkClient = \Mockery::mock(PostmarkClient::class);
        $postmarkClient
            ->shouldReceive('sendEmail')
            ->withArgs(function ($from, $to, $subject, $htmlBody, $textBody) use ($newEmail, $mockRenderedMessage) {
                $this->assertEquals('robot@simplytestable.com', $from);
                $this->assertEquals($newEmail, $to);
                $this->assertEquals('[Simply Testable] Confirm your email address change', $subject);
                $this->assertNull($htmlBody);
                $this->assertEquals($mockRenderedMessage, $textBody);

                return true;
            });

        $mailer = new Mailer(
            self::$container->get(MailConfiguration::class),
            self::$container->get(RouterInterface::class),
            $postmarkClient,
            $twig
        );

        $mailer->sendEmailChangeConfirmationToken($newEmail, $currentEmail, $token);
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
