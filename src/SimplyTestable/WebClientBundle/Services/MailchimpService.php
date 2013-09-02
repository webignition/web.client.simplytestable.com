<?php
namespace SimplyTestable\WebClientBundle\Services;
use ZfrMailChimp\Client\MailChimpClient;

class MailchimpService {
    
    /**
     *
     * @var string
     */
    private $apiKey;
    
    
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
    public function __construct($apiKey, $lists) {
        $this->apiKey = $apiKey;
        
        foreach ($lists as $name => $id) {
            $this->lists[$name] = array(
                'id' => $id
            );
        }
    }    
    
    
    /**
     * 
     * @return MailChimpClient
     */
    public function getClient() {
        if (is_null($this->client)) {
            $this->client = new MailChimpClient($this->apiKey);
        }
        
        return $this->client;
    }
    
    
    /**
     * 
     * @param string $listId
     * @param string $email
     * @return boolean
     */
    public function listContains($listId, $email) {
        $email = strtolower($email);        
        $members = $this->getListMembers($listId);
        
        foreach ($members as $member) {
            if (strtolower($member['email']) == $email) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * 
     * @param string $listId
     * @param string $email
     * @return array
     */
    private function getMember($listId, $email) {       
        $members = $this->getListMembers($listId);
        
        foreach ($members as $member) {
            if (strtolower($member['email']) == $email) {
                return $member;
            }
        }
        
        return null;
    }
    
    
    public function subscribe($listId, $email) {
//        var_dump($listId, $email, $this->listContains($listId, $email), $this->lists['introduction']);
//        exit();
        
        if ($this->listContains($listId, $email)) {
            return true;
        }
        
        $this->getClient()->subscribe(array(
            'id' => $this->lists[$listId]['id'],
            'email' => array(
                'email' => $email
            ),
            'double_optin' => false
        ));
        
        return true;      
    }
    
    public function unsubscribe($listId, $email) {
        $member = $this->getMember($listId, $email);
        if (is_null($member)) {
            return true;
        }
   
        $this->getClient()->unsubscribe(array(
            'id' => $this->lists[$listId]['id'],
            'email' => $member,
            'delete_member' => false
        ));
        
        return true;
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
            
            $this->lists[$listId]['members'] = $response['data'];         
        }
        
        return $this->lists[$listId]['members'];
    }
    
}