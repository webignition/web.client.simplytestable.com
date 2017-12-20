<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services\Mail;

use SimplyTestable\WebClientBundle\Services\Mail\Configuration;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $configurationValues = [
        Configuration::SENDERS_KEY => [
            'default' => [
                'email' => 'robot@simplytestable.com',
                'Simply Testable Robot',
            ],
        ],
        Configuration::MESSAGE_PROPERTIES_COLLECTION_KEY => [
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
        $this->setExpectedException(
            MailConfigurationException::class,
            $expectedExceptionMessage,
            $expectedExceptionCode
        );

        $configuration = new Configuration($configurationValues);

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
                'expectedExceptionMessage' => Configuration::EXCEPTION_NO_MAIL_SENDERS_SET_MESSAGE,
                'expectedExceptionCode' => Configuration::EXCEPTION_NO_MAIL_SENDERS_SET_CODE,
            ],
            'no senders defined' => [
                'configurationValues' => [
                    Configuration::SENDERS_KEY => [],
                ],
                'senderKey' => 'foo',
                'expectedExceptionMessage' => 'Sender "foo" not set',
                'expectedExceptionCode' => Configuration::EXCEPTION_SENDER_NOT_SET_CODE,
            ],
            'sender not set' => [
                'configurationValues' => $this->configurationValues,
                'senderKey' => 'bar',
                'expectedExceptionMessage' => 'Sender "bar" not set',
                'expectedExceptionCode' => Configuration::EXCEPTION_SENDER_NOT_SET_CODE,
            ],
        ];
    }

    public function testGetSenderSuccess()
    {
        $configuration = new Configuration($this->configurationValues);
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
        $this->setExpectedException(
            MailConfigurationException::class,
            $expectedExceptionMessage,
            $expectedExceptionCode
        );

        $configuration = new Configuration($configurationValues);

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
                'expectedExceptionMessage' => Configuration::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_MESSAGE,
                'expectedExceptionCode' => Configuration::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_CODE,
            ],
            'no message properties defined' => [
                'configurationValues' => [
                    Configuration::MESSAGE_PROPERTIES_COLLECTION_KEY => [],
                ],
                'messageKey' => 'foo',
                'expectedExceptionMessage' => 'Message properties "foo" not set',
                'expectedExceptionCode' => Configuration::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE,
            ],
            'message properties not set' => [
                'configurationValues' => $this->configurationValues,
                'messageKey' => 'bar',
                'expectedExceptionMessage' => 'Message properties "bar" not set',
                'expectedExceptionCode' => Configuration::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE,
            ],
        ];
    }

    public function testGetMessagePropertiesSuccess()
    {
        $configuration = new Configuration($this->configurationValues);
        $messageProperties = $configuration->getMessageProperties('user_creation_confirmation');

        $this->assertEquals([
            'subject' => '[Simply Testable] Activate your account',
        ], $messageProperties);
    }
}
