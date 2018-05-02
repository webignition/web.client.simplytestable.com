<?php

namespace Tests\WebClientBundle\Factory;

use Mockery\Mock;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class MockPostmarkMessageFactory
{
     const SUBJECT_RESET_YOUR_PASSWORD = '[Simply Testable] Reset your password';
     const SUBJECT_ACTIVATE_YOUR_ACCOUNT = '[Simply Testable] Activate your account';
     const SUBJECT_CONFIRM_EMAIL_ADDRESS_CHANGE = '[Simply Testable] Confirm your email address change';

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
            self::SUBJECT_ACTIVATE_YOUR_ACCOUNT,
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
            self::SUBJECT_CONFIRM_EMAIL_ADDRESS_CHANGE,
            $responseData
        );
    }

    /**
     * @param string $to
     * @param array $responseData
     * @param mixed $textMessage
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockTeamInvitePostmarkMessage($to, $responseData, $textMessage = true)
    {
        return MockPostmarkMessageFactory::createMockPostmarkMessage(
            $to,
            '[Simply Testable] You have been invited to join the Team Name team',
            $responseData,
            $textMessage
        );
    }

    /**
     * @param string $to
     * @param mixed $textMessage
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockTeamInviteSuccessPostmarkMessage($to, $textMessage = true)
    {
        return MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
            $to,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ],
            $textMessage
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
            self::SUBJECT_RESET_YOUR_PASSWORD,
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
