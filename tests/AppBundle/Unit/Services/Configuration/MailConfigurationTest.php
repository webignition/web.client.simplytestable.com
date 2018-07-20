<?php

namespace Tests\AppBundle\Unit\Services\Configuration;

use AppBundle\Services\Configuration\MailConfiguration;
use AppBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
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
     *
     * @param array $configurationValues
     * @param string $senderKey
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws MailConfigurationException
     */
    public function testGetSenderException(
        $configurationValues,
        $senderKey,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->expectException(MailConfigurationException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $configuration = new MailConfiguration($configurationValues);

        $configuration->getSender($senderKey);
    }

    /**
     * @return array
     */
    public function getSenderExceptionDataProvider()
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
     *
     * @param array $configurationValues
     * @param string $messageKey
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws MailConfigurationException
     */
    public function testGetMessagePropertiesException(
        $configurationValues,
        $messageKey,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->expectException(MailConfigurationException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $configuration = new MailConfiguration($configurationValues);

        $configuration->getMessageProperties($messageKey);
    }

    /**
     * @return array
     */
    public function getMessagePropertiesExceptionDataProvider()
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
