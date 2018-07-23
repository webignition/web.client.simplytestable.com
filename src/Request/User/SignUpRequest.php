<?php

namespace App\Request\User;

class SignUpRequest extends AbstractUserAccountRequest
{
    const PARAMETER_PLAN = 'plan';

    /**
     * @var string
     */
    private $plan;

    /**
     * @param string $email
     * @param string $password
     * @param string $plan
     */
    public function __construct($email, $password, $plan)
    {
        parent::__construct($email, $password);

        $this->plan = $plan;
    }

    /**
     * @return string
     */
    public function getPlan()
    {
        return $this->plan;
    }
}
