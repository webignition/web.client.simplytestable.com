<?php

namespace Tests\WebClientBundle\Factory;

use Mockery\Mock;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class MockPostmarkMessageFactory
{
     const SUBJECT_RESET_YOUR_PASSWORD = '[Simply Testable] Reset your password';

    /**
     * @param string $to
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockActivateAccountPostmarkMessage($to, $responseData)
    {
        return self::createMockPostmarkMessage(
            $to,
            '[Simply Testable] Activate your account',
            $responseData
        );
    }

    /**
     * @param string $to
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockConfirmEmailAddressPostmarkMessage($to, $responseData)
    {
        return self::createMockPostmarkMessage(
            $to,
            '[Simply Testable] Confirm your email address change',
            $responseData
        );
    }

    /**
     * @param string $to
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockTeamInvitePostmarkMessage($to, $responseData)
    {
        return MockPostmarkMessageFactory::createMockPostmarkMessage(
            $to,
            '[Simply Testable] You have been invited to join the Team Name team',
            $responseData
        );
    }

    /**
     * @param string $to
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockTeamInviteSuccessPostmarkMessage($to)
    {
        return MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
            $to,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        );
    }

    /**
     * @param string $to
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockResetPasswordPostmarkMessage($to, $responseData)
    {
        return MockPostmarkMessageFactory::createMockPostmarkMessage(
            $to,
            '[Simply Testable] Reset your password',
            $responseData
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param array $responseData
     * @param mixed $textMessage
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockPostmarkMessage($to, $subject, $responseData, $textMessage = true)
    {
        return MockFactory::createPostmarkMessage([
            'setFrom' => true,
            'setSubject' => [
                'with' => $subject,
            ],
            'setTextMessage' => $textMessage,
            'addTo' => [
                'with' => $to,
            ],
            'send' => [
                'return' => json_encode($responseData),
            ],
        ]);
    }
}
