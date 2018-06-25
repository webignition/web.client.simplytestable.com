<?php

namespace SimplyTestable\WebClientBundle\Exception;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use webignition\GuzzleHttp\Exception\CurlException\Exception as CurlException;

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

            $message = $previous->getMessage();
            $code = $previous->getCurlCode();
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
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        /* @var \GuzzleHttp\Exception\ClientException $previousException */
        $previousException = $this->getPrevious();

        if ($previousException instanceof RequestException) {
            /* @var RequestException $previousException */
            return $previousException->getResponse();
        }

        return null;
    }
}
