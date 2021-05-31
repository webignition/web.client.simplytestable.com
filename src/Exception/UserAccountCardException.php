<?php

namespace App\Exception;

class UserAccountCardException extends \Exception
{
    /**
     *
     * @var string
     */
    private $param;


    /**
     *
     * @var string
     */
    protected $stripeCode;


    /**
     *
     * @param string $message
     * @param string $param
     * @param string $stripeCode
     */
    public function __construct($message, $param, $stripeCode)
    {
        parent::__construct($message, null, null);
        $this->param = $param;
        $this->stripeCode = $stripeCode;
    }


    /**
     *
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }


    /**
     *
     * @return string
     */
    public function getStripeCode()
    {
        return $this->stripeCode;
    }
}
