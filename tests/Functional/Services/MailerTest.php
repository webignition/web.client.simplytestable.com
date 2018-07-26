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
        $mockRenderedMessage = 'Mock rendered message';

        $twig = MockFactory::createTwig([
            'render' => [
                'withArgs' => function ($viewName, $parameters) {
                    $this->assertEquals(Mailer::VIEW_SIGN_UP_CONFIRMATION, $viewName);
                    $this->assertEquals(
                        [
                            'confirmation_url' => 'http://localhost/signup/confirm/user@example.com/?token=token-value',
                            'confirmation_code' => 'token-value',
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
            ->withArgs(function ($from, $to, $subject, $htmlBody, $textBody) use ($mockRenderedMessage) {
                $this->assertEquals('robot@simplytestable.com', $from);
                $this->assertEquals('user@example.com', $to);
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

        $mailer->sendSignUpConfirmationToken('user@example.com', 'token-value');
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
