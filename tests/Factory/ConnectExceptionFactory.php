<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Factory;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;

class ConnectExceptionFactory
{
    /**
     * Transforms a stringified curl message into a ConnectException
     *
     * Usage example:
     * ConnectExceptionFactory::create('CURL/28 Operation timed out');
     */
    public static function create(string $curlMessage): ConnectException
    {
        $curlMessageParts = explode(' ', $curlMessage, 2);

        return new ConnectException(
            'cURL error ' . str_replace('CURL/', '', $curlMessageParts[0]) . ': ' . $curlMessageParts[1],
            new Request('GET', 'http://example.com/')
        );
    }
}
