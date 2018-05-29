<?php

namespace SimplyTestable\WebClientBundle\Request\User;

class SignInRequest
{
    const PARAMETER_EMAIL = 'email';
    const PARAMETER_PASSWORD = 'password';
    const PARAMETER_REDIRECT = 'redirect';
    const PARAMETER_STAY_SIGNED_IN = 'stay-signed-in';

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $redirect;

    /**
     * @var bool
     */
    private $staySignedIn;

    /**
     * @param string $email
     * @param string $password
     * @param string $redirect
     * @param bool $staySignedIn
     */
    public function __construct($email, $password, $redirect, $staySignedIn)
    {
        $this->email = $email;
        $this->password = $password;
        $this->redirect = $redirect;
        $this->staySignedIn = $staySignedIn;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @return bool
     */
    public function getStaySignedIn()
    {
        return $this->staySignedIn;
    }
}
