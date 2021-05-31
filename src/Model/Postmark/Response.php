<?php

namespace App\Model\Postmark;

class Response
{
    const NON_ERROR_RESPONSE_MESSAGE = 'OK';
    const ERROR_CODE_INVALID_TO_ADDRESS = 300;

    /**
     * @var array
     */
    private $responseData = [];


    /**
     * @param string $json
     */
    public function __construct($json)
    {
        $this->responseData = json_decode($json, true);
    }

    /**
     *
     * @return bool
     */
    public function isError()
    {
        return self::NON_ERROR_RESPONSE_MESSAGE !== $this->responseData['Message'];
    }

    /**
     * @return int|null
     */
    public function getErrorCode()
    {
        if (!$this->isError()) {
            return null;
        }

        return $this->responseData['ErrorCode'];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->responseData['Message'];
    }
}
