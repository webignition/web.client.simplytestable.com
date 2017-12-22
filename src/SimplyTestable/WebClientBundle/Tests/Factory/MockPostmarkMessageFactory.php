<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Mockery\Mock;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class MockPostmarkMessageFactory
{
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
     * @param string $subject
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    public static function createMockPostmarkMessage($to, $subject, $responseData)
    {
        return MockFactory::createPostmarkMessage([
            'setFrom' => true,
            'setSubject' => [
                'with' => $subject,
            ],
            'setTextMessage' => true,
            'addTo' => [
                'with' => $to,
            ],
            'send' => [
                'return' => json_encode($responseData),
            ],
        ]);
    }
}
