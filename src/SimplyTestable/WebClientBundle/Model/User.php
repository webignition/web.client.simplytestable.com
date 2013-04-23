<?php
namespace SimplyTestable\WebClientBundle\Model;

class User {    
    
    /**
     *
     * @var string
     */
    private $username = null;
    
    
    /**
     *
     * @var string
     */
    private $password = null;   
    
    
    /**
     * 
     * @param string $username
     * @param string $password
     */
    public function __construct($username = null, $password = null) {
        $this->setUsername($username)->setPassword($password);
    }
    
    
    /**
     * 
     * @param string $username
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
    
    
    /**
     * 
     * @param string $password
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @return boolean
     */
    public function equals(User $user) {
        return $this->getUsername() == $user->getUsername();
    }
    
}