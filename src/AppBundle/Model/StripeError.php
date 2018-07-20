<?php

namespace AppBundle\Model;

class StripeError
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $param;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $message
     * @param string $param
     * @param string $code
     */
    public function __construct($message, $param, $code)
    {
        if (is_null($message)) {
            $message = '';
        }

        if (is_null($param)) {
            $param = '';
        }

        if (is_null($code)) {
            $code = '';
        }

        $this->message = $message;
        $this->param = $param;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->message) && empty($this->param) && empty($this->code);
    }
}
