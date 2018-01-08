<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Guzzle\Http\Exception\CurlException;

class CurlExceptionFactory
{
    /**
     * @param string $error
     * @param int $code
     *
     * @return CurlException
     */
    public static function create($error, $code)
    {
        $curlException = new CurlException();
        $curlException->setError($error, $code);

        return $curlException;
    }
}
