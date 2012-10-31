<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;

class UserService extends CoreApplicationService {    
    
    const PUBLIC_USER_USERNAME = 'public';
    const PUBLIC_USER_PASSWORD = 'public';
    
    /**
     *
     * @var string
     */
    private $adminUserUsername;
    
    /**
     *
     * @var string
     */
    private $adminUserPassword;
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        $adminUserUsername,
        $adminUserPassword
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->adminUserUsername = $adminUserUsername;
        $this->adminUserPassword = $adminUserPassword;
    }     

    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getPublicUser() {
        $user = new User();
        $user->setUsername(static::PUBLIC_USER_USERNAME);
        $user->setPassword(static::PUBLIC_USER_PASSWORD);
        return $user;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getAdminUser() {
        $user = new User();
        $user->setUsername($this->adminUserUsername);
        $user->setPassword($this->adminUserPassword);
        return $user;        
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @return boolean
     */
    public function isPublicUser(User $user) {
        return $this->getPublicUser()->equals($user);
    }   
    
    public function create($email) {
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_create'), HTTP_METH_POST);
        $request->addPostFields(array(
            'email' => $email
        ));
        
        $userExistsResponseCode = 302;
        
        if ($this->getHttpClient()->redirectHandler()->isEnabled($userExistsResponseCode)) {
            $this->getHttpClient()->redirectHandler()->disable($userExistsResponseCode);
        }        
        
        try {
            $response = $this->getHttpClient()->getResponse($request);
            
            if ($response->getResponseCode() == $userExistsResponseCode) {
                return false;
            }
            
            return true;
        } catch (\webignition\Http\Client\CurlException $curlException) {     
            return null;
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            return null;
        }       
    }
    
    
    public function activate($token) {
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;
   
        $this->setUser($this->getAdminUser());
        
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_activate', array(
            'token' => $token
        )), HTTP_METH_POST);
        
        $response = $this->getHttpClient()->getResponse($request);
        
        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }        
        
        if ($response->getResponseCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }        
        
        if ($response->getResponseCode() == 400) {
            return false;
        }

        return true;      
    }
    
    
    
    /**
     * 
     * @param string $email
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    public function exists($email) {
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;
   
        $this->setUser($this->getAdminUser());
        
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_exists', array(
            'email' => $email
        )), HTTP_METH_POST);
        
        $response = $this->getHttpClient()->getResponse($request);
        
        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }        
        
        if ($response->getResponseCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        } 
        
        return $response->getResponseCode() == 200;         
    }

    
    /**
     * 
     * @param string $email
     * @return string
     * @throws CoreApplicationAdminRequestException
     */
    public function getConfirmationToken($email) {
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;
   
        $this->setUser($this->getAdminUser());
        
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_get_token', array(
            'email' => $email
        )));
        
        try {
            $response = $this->getHttpClient()->getResponse($request);
        } catch (\webignition\Http\Client\CurlException $curlException) {                 
            $response = null;
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            $response = null;
        }              
        
        if (is_null($response)) {
            return null;
        }
        
        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }
        
        if ($response->getResponseCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }

        return json_decode($response->getBody());
    }
    
}