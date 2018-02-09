<?php

namespace SimplyTestable\WebClientBundle\Exception;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

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

    /**
     * @return RequestInterface|null
     */
    public function getRequest()
    {
        $previousException = $this->getPrevious();

        if ($previousException instanceof RequestException) {
            /* @var RequestException $previousException */
            return $previousException->getRequest();
        }

        return null;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        $previousException = $this->getPrevious();

        if ($previousException instanceof RequestException) {
            /* @var RequestException $previousException */
            return $this->getRequest()->getResponse();
        }

        return null;
    }
}
