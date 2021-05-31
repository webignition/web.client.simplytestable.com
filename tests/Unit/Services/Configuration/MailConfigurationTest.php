<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Configuration;

use App\Services\Configuration\MailConfiguration;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    private $configurationValues = [
        MailConfiguration::SENDERS_KEY => [
            'default' => [
                'email' => 'robot@simplytestable.com',
                'Simply Testable Robot',
            ],
        ],
        MailConfiguration::MESSAGE_PROPERTIES_COLLECTION_KEY => [
            'user_creation_confirmation' => [
                'subject' => '[Simply Testable] Activate your account',
            ],
        ],
    ];

    /**
     * @dataProvider getSenderExceptionDataProvider
     */
    public function testGetSenderException(
        array $configurationValues,
        string $senderKey,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->expectException(MailConfigurationException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $configuration = new MailConfiguration($configurationValues);

        $configuration->getSender($senderKey);
    }

    public function getSenderExceptionDataProvider(): array
    {
        return [
            'no mail senders set' => [
                'configurationValues' => [],
                'senderKey' => 'foo',
                'expectedExceptionMessage' => MailConfiguration::EXCEPTION_NO_MAIL_SENDERS_SET_MESSAGE,
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_NO_MAIL_SENDERS_SET_CODE,
            ],
            'no senders defined' => [
                'configurationValues' => [
                    MailConfiguration::SENDERS_KEY => [],
                ],
                'senderKey' => 'foo',
                'expectedExceptionMessage' => 'Sender "foo" not set',
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_SENDER_NOT_SET_CODE,
            ],
            'sender not set' => [
                'configurationValues' => $this->configurationValues,
                'senderKey' => 'bar',
                'expectedExceptionMessage' => 'Sender "bar" not set',
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_SENDER_NOT_SET_CODE,
            ],
        ];
    }

    public function testGetSenderSuccess()
    {
        $configuration = new MailConfiguration($this->configurationValues);
        $sender = $configuration->getSender('default');

        $this->assertEquals([
            'email' => 'robot@simplytestable.com',
            'Simply Testable Robot',
        ], $sender);
    }

    /**
     * @dataProvider getMessagePropertiesExceptionDataProvider
     */
    public function testGetMessagePropertiesException(
        array $configurationValues,
        string $messageKey,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->expectException(MailConfigurationException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $configuration = new MailConfiguration($configurationValues);

        $configuration->getMessageProperties($messageKey);
    }

    public function getMessagePropertiesExceptionDataProvider(): array
    {
        return [
            'no message properties set' => [
                'configurationValues' => [],
                'messageKey' => 'foo',
                'expectedExceptionMessage' => MailConfiguration::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_MESSAGE,
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_CODE,
            ],
            'no message properties defined' => [
                'configurationValues' => [
                    MailConfiguration::MESSAGE_PROPERTIES_COLLECTION_KEY => [],
                ],
                'messageKey' => 'foo',
                'expectedExceptionMessage' => 'Message properties "foo" not set',
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE,
            ],
            'message properties not set' => [
                'configurationValues' => $this->configurationValues,
                'messageKey' => 'bar',
                'expectedExceptionMessage' => 'Message properties "bar" not set',
                'expectedExceptionCode' => MailConfiguration::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE,
            ],
        ];
    }

    public function testGetMessagePropertiesSuccess()
    {
        $configuration = new MailConfiguration($this->configurationValues);
        $messageProperties = $configuration->getMessageProperties('user_creation_confirmation');

        $this->assertEquals([
            'subject' => '[Simply Testable] Activate your account',
        ], $messageProperties);
    }
}
