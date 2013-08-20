<?php
namespace SimplyTestable\WebClientBundle\Services;
use ZfrMailChimp\Client\MailChimpClient;

class MailchimpService {
    
    
    /**
     *
     * @var MailChimpClient 
     */
    private $client;
    
    private $lists = array();
    
    
    /**
     * 
     * @param string $apiKey
     */
    public function __construct($apiKey, $updatesListId, $announcementsListId) {
        $this->client = new MailChimpClient($apiKey);
        
        $this->lists['updates'] = array(
            'id' => $updatesListId
        );
        
        $this->lists['announcements'] = array(
            'id' => $announcementsListId
        );
    }
    
    
    /**
     * 
     * @return MailChimpClient
     */
    public function getClient() {
        return $this->client;
    }
    
    
    /**
     * 
     * @param string $listId
     * @param string $email
     * @return boolean
     */
    public function listContains($listId, $email) {
        return in_array(strtolower($email), $this->getListMembers($listId));
    }
    
    
    /**
     * 
     * @param string $listId
     * @return array
     */
    private function getListMembers($listId) {
        if (!isset($this->lists[$listId]['members'])) {
            $this->lists[$listId]['members'] = array();
            $response = $this->getClient()->getListMembers(array(
                'id' => $this->lists[$listId]['id']
            )); 

            foreach ($response['data'] as $member) {
                $this->lists[$listId]['members'][] = strtolower($member['email']);
            }            
        }
        
        return $this->lists[$listId]['members'];
    }
    
}