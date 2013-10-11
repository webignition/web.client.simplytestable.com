<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;

class AvailableTaskTypeService {
    
    const KEY_DEFAULT = 'default';
    const KEY_EARLY_ACCESS = 'early_access';
    const KEY_AUTHENTICATED = 'authenticated';
    
    /**
     *
     * @var array
     */
    private $allTaskTypes;
    
    /**
     *
     * @var array
     */
    private $earlyAccessUsers;
    
    
    /**
     *
     * @var User
     */
    private $user;
    
    
    /**
     *
     * @var boolean
     */
    private $isAuthenticated;
    
    public function __construct($allTaskTypes, $earlyAccessUsers) {
        $this->allTaskTypes = $allTaskTypes;
        $this->earlyAccessUsers = $earlyAccessUsers;
    } 
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     */
    public function setUser(User $user) {
        $this->user = $user;
    }
    
    
    /**
     * 
     * @param boolean $isAuthenticated
     */
    public function setIsAuthenticated($isAuthenticated) {
        $this->isAuthenticated = $isAuthenticated;
    }
    
    
    
    /**
     * 
     * @return array
     */
    public function get() {        
        $availableTaskTypes = array();
        
        if (isset($this->allTaskTypes[self::KEY_DEFAULT])) {
            $availableTaskTypes = array_merge($availableTaskTypes, $this->allTaskTypes[self::KEY_DEFAULT]);
        }
        
        if ($this->isEarlyAccessUser() && isset($this->allTaskTypes[self::KEY_EARLY_ACCESS])) {
            $availableTaskTypes = array_merge($availableTaskTypes, $this->allTaskTypes[self::KEY_EARLY_ACCESS]);
        }
        
        if ($this->isAuthenticated && isset($this->allTaskTypes[self::KEY_AUTHENTICATED])) {
            $availableTaskTypes = array_merge($availableTaskTypes, $this->allTaskTypes[self::KEY_AUTHENTICATED]);
        }
        
        return $availableTaskTypes;
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    private function isEarlyAccessUser() {
        if (!$this->user instanceof \SimplyTestable\WebClientBundle\Model\User) {
            return false;
        }
        
        return in_array($this->user->getUsername(), $this->earlyAccessUsers);
    }
    
}