<?php

namespace AppBundle\Request\User;

class SignInRequest extends AbstractUserAccountRequest
{
    const PARAMETER_REDIRECT = 'redirect';
    const PARAMETER_STAY_SIGNED_IN = 'stay-signed-in';

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
        parent::__construct($email, $password);

        $this->redirect = $redirect;
        $this->staySignedIn = $staySignedIn;
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
