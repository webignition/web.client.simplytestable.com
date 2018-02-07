<?php

namespace SimplyTestable\WebClientBundle\Exception;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;

class CoreApplicationRequestException extends \Exception
{
    /**
     * @var bool
     */
    private $isCurlException;

    /**
     * @var bool
     */
    private $isHttpException;

    /**
     * @param \Exception $previous
     */
    public function __construct(\Exception $previous)
    {
        $message = 'Unknown request exception';
        $code = 0;

        if ($previous instanceof CurlException) {
            /* @var CurlException $previous */

            $message = $previous->getError();
            $code = $previous->getErrorNo();
            $this->isCurlException = true;
        }

        if ($previous instanceof BadResponseException) {
            /* @var BadResponseException $previous */
            $response = $previous->getResponse();

            $message = $response->getReasonPhrase();
            $code = $response->getStatusCode();
            $this->isHttpException = true;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return bool
     */
    public function isHttpException()
    {
        return true === $this->isHttpException;
    }

    /**
     * @return bool
     */
    public function isCurlException()
    {
        return true === $this->isCurlException;
    }
}
