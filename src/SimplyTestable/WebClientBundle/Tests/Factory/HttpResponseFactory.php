<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Guzzle\Http\Message\Response;

class HttpResponseFactory
{
    /**
     * @param string $message
     *
     * @return Response
     */
    public static function create($message)
    {
        return Response::fromMessage($message);
    }

    /**
     * @param mixed $data
     *
     * @return Response
     */
    public static function createJsonResponse($data)
    {
        return self::create("HTTP/1.1 200\nContent-Type:application/json\n\n" . json_encode($data));
    }
}
