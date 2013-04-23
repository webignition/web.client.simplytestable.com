<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

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
    
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\UserSerializerService
     */
    private $userSerializerService;
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        $adminUserUsername,
        $adminUserPassword,
        \Symfony\Component\HttpFoundation\Session\Session $session,
        \SimplyTestable\WebClientBundle\Services\UserSerializerService $userSerializerService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->adminUserUsername = $adminUserUsername;
        $this->adminUserPassword = $adminUserPassword;
        $this->session = $session;
        $this->userSerializerService = $userSerializerService;
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
    public function isSpecialUser(User $user) {
        return $this->isPublicUser($user) || $this->isAdminUser($user);
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @return boolean
     */
    public function isPublicUser(User $user) {
        $comparatorUser = new User();
        $comparatorUser->setUsername(strtolower($user->getUsername()));
        
        return $this->getPublicUser()->equals($comparatorUser);
    } 
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @return boolean
     */
    public function isAdminUser(User $user) {
        $comparatorUser = new User();
        $comparatorUser->setUsername(strtolower($user->getUsername()));
        
        return $this->getAdminUser()->equals($comparatorUser);
    }
    
    
    
    /**
     * 
     * @param string $token
     * @param string $password
     * @return null|boolean
     * @throws UserServiceException
     */
    public function resetPassword($token, $password) {
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_reset_password', array('token' => $token)), \Guzzle\Http\Message\Request::POST);
        $request->addPostFields(array(
            'password' => $password
        ));
        
        try {
            $response = $this->getHttpClient()->getResponse($request);            
            return ($response->getResponseCode() == 200) ? true : $response->getResponseCode();
        } catch (\webignition\Http\Client\CurlException $curlException) {     
            return $curlException->getCode();
        }
    }
    
    
    /**
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function authenticate() {
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user', array(
            'email' => $this->getUser()->getUsername(),
            'password' => $this->getUser()->getPassword()
        )), \Guzzle\Http\Message\Request::POST);
        
        $response = $this->getHttpClient()->getResponse($request);
        return $response->getResponseCode() == 200;
    }
    
    
    public function create($email, $password) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
                $this->getUrl('user_create'),
                null,
                array(
            'email' => $email,
            'password' => $password
        ));
        
        $this->addAuthorisationToRequest($request);        
        $request->getParams()->set('redirect.disable', true);
        
        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            if ($badResponseException->getResponse()->getStatusCode() == 401) {
                throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
            }
            
            return $badResponseException->getResponse()->getStatusCode();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }
    }
    
    
    public function activate($token) {   
        $this->setUser($this->getAdminUser());
        
        $request = $this->webResourceService->getHttpClientService()->postRequest($this->getUrl('user_activate', array(
            'token' => $token
        )));
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();                        
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }
        
        $this->setUser($this->getPublicUser());
        
        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }        

        if ($response->getStatusCode() == 400) {
            return false;
        }         
        
        return $response->getStatusCode() == 200 ? true : $response->getStatusCode();        
    }    
    
    
    /**
     * 
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    public function exists($email = null) {        
        /* @var $currentUser User */        
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;        
        if (is_null($currentUser)) {
            return false;
        }
        
        if ($this->isSpecialUser($currentUser)) {
            $currentUser = null;
        }
        
        $userEmail = (is_null($currentUser)) ? $email : $currentUser->getUsername();      
   
        $this->setUser($this->getAdminUser());
        
        $request = $this->webResourceService->getHttpClientService()->postRequest($this->getUrl('user_exists', array(
            'email' => $userEmail
        )));
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        }
        
        if (is_null($currentUser)) {
            $currentUser = $this->getPublicUser();
        }    
        
        $this->setUser($currentUser);    
        
        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        } 
        
        if (is_null($response)) {
            return null;
        }
        
        return $response->getStatusCode() == 200;         
    }
    
    
    /**
     * 
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    public function isEnabled() {
        if (!$this->exists()) {
            return false;
        }
        
        /* @var $currentUser User */
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;        
   
        $this->setUser($this->getAdminUser());
        
        $request = $this->getAuthorisedHttpRequest($this->getUrl('user_is_enabled', array(
            'email' => $currentUser->getUsername()
        )), \Guzzle\Http\Message\Request::POST);
        
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
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('user_get_token', array(
            'email' => $email
        )));
        
        $currentUser = ($this->hasUser()) ? $this->getUser() : null;
   
        $this->setUser($this->getAdminUser());
        
        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $response = null;
        }            
        
        if (is_null($response)) {
            return null;
        }
        
        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }
        
        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }

        return json_decode($response->getBody());
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     */
    public function setUser(User $user) {
        $this->session->set('user', $this->userSerializerService->serialize($user));
        parent::setUser($user);
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getUser() {                
        if (is_null($this->session->get('user'))) {
            $this->setUser($this->getPublicUser());
        }
        
        $user = $this->userSerializerService->unserialize($this->session->get('user'));
        
        if ($this->isPublicUser($user)) {
            $user = $this->getPublicUser();
        }
        
        parent::setUser($user);
        
        return parent::getUser();
    }
    
    
    public function clearUser() {
        $this->session->set('user', null);
    }
    
}