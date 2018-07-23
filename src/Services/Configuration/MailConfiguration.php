<?php
namespace App\Services\Configuration;

use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\HttpFoundation\ParameterBag;

class MailConfiguration
{
    const SENDERS_KEY = 'senders';
    const MESSAGE_PROPERTIES_COLLECTION_KEY = 'message_properties';

    const EXCEPTION_NO_MAIL_SENDERS_SET_MESSAGE = 'No mail senders set';
    const EXCEPTION_NO_MAIL_SENDERS_SET_CODE = 1;
    const EXCEPTION_SENDER_NOT_SET_MESSAGE = 'Sender "%s" not set';
    const EXCEPTION_SENDER_NOT_SET_CODE = 2;
    const EXCEPTION_NO_MESSAGE_PROPERTIES_SET_MESSAGE = 'No message properties set';
    const EXCEPTION_NO_MESSAGE_PROPERTIES_SET_CODE = 3;
    const EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_MESSAGE = 'Message properties "%s" not set';
    const EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE = 4;

    /**
     * @var ParameterBag
     */
    private $configuration;

    /**
     * @param array $configurationValues
     */
    public function __construct($configurationValues)
    {
        $this->configuration = new ParameterBag($configurationValues);
    }

    /**
     * @param string $key
     * @return array
     *
     * @throws MailConfigurationException
     */
    public function getSender($key)
    {
        $senders = $this->getSenders();

        if (!isset($senders[$key])) {
            throw new MailConfigurationException(
                sprintf(self::EXCEPTION_SENDER_NOT_SET_MESSAGE, $key),
                self::EXCEPTION_SENDER_NOT_SET_CODE
            );
        }

        return $senders[$key];
    }

    /**
     * @param string $key
     * @return array
     *
     * @throws MailConfigurationException
     */
    public function getMessageProperties($key)
    {
        $propertiesCollection = $this->getMessagePropertiesCollection();
        if (!isset($propertiesCollection[$key])) {
            throw new MailConfigurationException(
                sprintf(self::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_MESSAGE, $key),
                self::EXCEPTION_MESSAGE_PROPERTIES_NOT_SET_CODE
            );
        }

        return $propertiesCollection[$key];
    }

    /**
     * @return mixed
     * @throws MailConfigurationException
     */
    private function getMessagePropertiesCollection()
    {
        if (!$this->configuration->has(self::MESSAGE_PROPERTIES_COLLECTION_KEY)) {
            throw new MailConfigurationException(
                self::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_MESSAGE,
                self::EXCEPTION_NO_MESSAGE_PROPERTIES_SET_CODE
            );
        }

        return $this->configuration->get(self::MESSAGE_PROPERTIES_COLLECTION_KEY);
    }

    /**
     * @return array
     * @throws MailConfigurationException
     */
    private function getSenders()
    {
        if (!$this->configuration->has(self::SENDERS_KEY)) {
            throw new MailConfigurationException(
                self::EXCEPTION_NO_MAIL_SENDERS_SET_MESSAGE,
                self::EXCEPTION_NO_MAIL_SENDERS_SET_CODE
            );
        }

        return $this->configuration->get(self::SENDERS_KEY);
    }
}
