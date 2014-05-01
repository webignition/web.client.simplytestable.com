<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use ZfrMailChimp\Client\MailChimpClient;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;

class Service {
    
    const LIST_MEMBERS_MAX_LIMIT = 100;
    
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
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService 
     */
    private $listRecipientsService;
    
    
    /**
     * 
     * @param string $apiKey
     */
    public function __construct($apiKey, ListRecipientsService $listRecipientsService) {
        $this->apiKey = $apiKey;
        $this->listRecipientsService = $listRecipientsService;
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
     * @param string $listName
     * @param string $email
     * @return boolean
     */
    public function listContains($listName, $email) {
        return $this->listRecipientsService->get($listName)->contains($email);
    }
    
    
    /**
     * 
     * @param string $listName
     * @param string $email
     * @return boolean
     */
    public function subscribe($listName, $email) {        
        if ($this->listContains($listName, $email)) {
            return true;
        }
        
        $this->getClient()->subscribe(array(
            'id' => $this->listRecipientsService->getListId($listName),
            'email' => array(
                'email' => $email
            ),
            'double_optin' => false
        ));
        
        return true;      
    }
    
    
    /**
     * 
     * @param string $listName
     * @param string $email
     * @return boolean
     */
    public function unsubscribe($listName, $email) {
        if (!$this->listContains($listName, $email)) {
            return true;
        }
   
        $this->getClient()->unsubscribe(array(
            'id' => $this->listRecipientsService->getListId($listName),
            'email' => array(
                'email' => $email
            ),
            'delete_member' => false
        ));
        
        return true;
    }
    
    
    /**
     * 
     * @param string $listName
     * @return array
     */
    public function retrieveMembers($listName) {
        $listLength = null;
        $currentPage = 0;
        $members = array();
        
        while (is_null($listLength) || count($members) < $listLength) {
            $response = $this->getClient()->getListMembers(array(
                'id' => $this->listRecipientsService->getListId($listName),
                'opts' => array(
                    'limit' => self::LIST_MEMBERS_MAX_LIMIT,
                    'start' => $currentPage
                )
            ));
            
            if (is_null($listLength)) {
                $listLength = $response['total'];
            }
            
            $currentPage++;
            
            $members = array_merge($members, $response['data']);
        }
        
        return $members;
    }  
    
}