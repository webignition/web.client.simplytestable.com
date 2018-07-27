<?php

namespace App\Tests\Functional\Services;

use App\Model\Team\Invite;
use App\Services\Configuration\MailConfiguration;
use App\Services\Mailer;
use App\Tests\Factory\MockFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Mockery\MockInterface;
use Postmark\PostmarkClient;
use Symfony\Component\Routing\RouterInterface;

class MailerTest extends AbstractBaseTestCase
{
    const MOCK_RENDERED_MESSAGE = 'Mock rendered message';
    const TOKEN = 'token-value';
    const TEAM_NAME = 'Team Name';


    public function testSendSignUpConfirmationToken()
    {
        $email = 'user@example.com';

        $expectedViewName = Mailer::VIEW_SIGN_UP_CONFIRMATION;
        $expectedViewParameters = [
            'confirmation_url' => 'http://localhost/signup/confirm/user@example.com/?token=' . self::TOKEN,
            'confirmation_code' => self::TOKEN,
        ];

        $twig = $this->createTwig($expectedViewName, $expectedViewParameters);
        $postmarkClient = $this->createPostmarkClient($email, 'Activate your account');

        $mailer = $this->createMailer($postmarkClient, $twig);
        $mailer->sendSignUpConfirmationToken($email, self::TOKEN);
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

        $mailer = $this->createMailer($postmarkClient, MockFactory::createTwig());
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

        $expectedViewName = Mailer::VIEW_EMAIL_CHANGE_CONFIRMATION;
        $expectedViewParameters = [
            'confirmation_url' => 'http://localhost/account/?token=' . self::TOKEN,
            'confirmation_code' => self::TOKEN,
            'current_email' => $currentEmail,
            'new_email' => $newEmail,
        ];

        $twig = $this->createTwig($expectedViewName, $expectedViewParameters);
        $postmarkClient = $this->createPostmarkClient($newEmail, 'Confirm your email address change');

        $mailer = $this->createMailer($postmarkClient, $twig);
        $mailer->sendEmailChangeConfirmationToken($newEmail, $currentEmail, self::TOKEN);
    }

    public function testTeamInviteForExistingUser()
    {
        $expectedViewName = Mailer::VIEW_TEAM_INVITE_EXISTING_USER;
        $expectedViewParameters = [
            'team_name' => self::TEAM_NAME,
            'account_team_page_url' => 'http://localhost/account/team/',
        ];

        $invitee = 'invitee@exaple.com';

        $invite = new Invite([
            'user' => $invitee,
            'team' => self::TEAM_NAME,
            'token' => self::TOKEN,
        ]);

        $twig = $this->createTwig($expectedViewName, $expectedViewParameters);
        $postmarkClient = $this->createPostmarkClient(
            $invitee,
            'You have been invited to join the ' . $invite->getTeam() . ' team'
        );

        $mailer = $this->createMailer($postmarkClient, $twig);
        $mailer->sendTeamInviteForExistingUser($invite);
    }

    public function testTeamInviteForNewUser()
    {
        $expectedViewName = Mailer::VIEW_TEAM_INVITE_NEW_USER;
        $expectedViewParameters = [
            'team_name' => self::TEAM_NAME,
            'confirmation_url' => 'http://localhost/signup/invite/' . self::TOKEN . '/',
        ];

        $invitee = 'invitee@exaple.com';

        $invite = new Invite([
            'user' => $invitee,
            'team' => self::TEAM_NAME,
            'token' => self::TOKEN,
        ]);

        $twig = $this->createTwig($expectedViewName, $expectedViewParameters);
        $postmarkClient = $this->createPostmarkClient(
            $invitee,
            'You have been invited to join the ' . $invite->getTeam() . ' team'
        );

        $mailer = $this->createMailer($postmarkClient, $twig);
        $mailer->sendTeamInviteForNewUser($invite);
    }

    private function createMailer(PostmarkClient $postmarkClient, \Twig_Environment $twig)
    {
        return new Mailer(
            self::$container->get(MailConfiguration::class),
            self::$container->get(RouterInterface::class),
            $postmarkClient,
            $twig
        );
    }

    private function createTwig(string $expectedViewName, array $expectedViewParameters)
    {
        return MockFactory::createTwig([
            'render' => [
                'withArgs' => function ($viewName, $parameters) use ($expectedViewName, $expectedViewParameters) {
                    $this->assertEquals($expectedViewName, $viewName);
                    $this->assertEquals($expectedViewParameters, $parameters);

                    return true;
                },
                'return' => self::MOCK_RENDERED_MESSAGE,
            ],
        ]);
    }

    private function createPostmarkClient(string $expectedTo, string $expectedSubjectSuffix)
    {
        /* @var PostmarkClient|MockInterface $postmarkClient */
        $postmarkClient = \Mockery::mock(PostmarkClient::class);
        $postmarkClient
            ->shouldReceive('sendEmail')
            ->withArgs(function ($from, $to, $subject, $htmlBody, $textBody) use ($expectedTo, $expectedSubjectSuffix) {
                $this->assertEquals('robot@simplytestable.com', $from);
                $this->assertEquals($expectedTo, $to);
                $this->assertEquals('[Simply Testable] ' . $expectedSubjectSuffix, $subject);
                $this->assertNull($htmlBody);
                $this->assertEquals(self::MOCK_RENDERED_MESSAGE, $textBody);

                return true;
            });

        return $postmarkClient;
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
