<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

class TestService extends Service {
    
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

        return true;
    }
    
}