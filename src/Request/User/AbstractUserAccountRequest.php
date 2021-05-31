<?php

namespace App\Request\User;

abstract class AbstractUserAccountRequest extends AbstractUserRequest
{
    const PARAMETER_PASSWORD = 'password';

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $email
     * @param string $password
     */
    public function __construct($email, $password)
    {
        parent::__construct($email);

        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
