<?php
namespace SimplyTestable\WebClientBundle\Services\Mail;

use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class Configuration {
    
    const SENDERS_KEY = 'senders';
    const MESSAGE_PROPERTIES_COLLECTION_KEY = 'message_properties';

    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $configuration;

    
    /**
     * 
     * @param array $configuration
     */
    public function __construct($configuration) {
        $this->configuration = new \Symfony\Component\HttpFoundation\ParameterBag($configuration);
    }   
    
    
    /**
     * 
     * @param string $key
     * @return array
     * @throws MailConfigurationException
     */
    public function getSender($key) {
        $senders = $this->getSenders();
        if (!isset($senders[$key])) {
            throw new MailConfigurationException('Sender "'.$key.'" not set', 2);
        }
        
        return $senders[$key];
    }
    
    
    /**
     * 
     * @param string $key
     * @return array
     * @throws MailConfigurationException
     */
    public function getMessageProperties($key) {
        $propertiesCollection = $this->getMessagePropertiesCollection();
        if (!isset($propertiesCollection[$key])) {
            throw new MailConfigurationException('Message properties "'.$key.'" not set', 4);
        }
        
        return $propertiesCollection[$key];
    }
    
    
    private function getMessagePropertiesCollection() {
        if (!$this->configuration->has(self::MESSAGE_PROPERTIES_COLLECTION_KEY)) {
            throw new MailConfigurationException('No message properties set', 3);
        }
        
        return $this->configuration->get(self::MESSAGE_PROPERTIES_COLLECTION_KEY);        
    }
    
    
    /**
     * 
     * @return array
     * @throws MailConfigurationException
     */
    private function getSenders() {
        if (!$this->configuration->has(self::SENDERS_KEY)) {
            throw new MailConfigurationException('No mail senders set', 1);
        }
        
        return $this->configuration->get(self::SENDERS_KEY);
    }
}