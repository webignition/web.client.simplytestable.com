<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Guzzle\Http\Message\Response;

class HttpResponseFactory
{
    /**
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     *
     * @return Response
     */
    public static function create($statusCode, $headers = [], $body = '')
    {
        $headersStringParts = [];
        foreach ($headers as $key => $value) {
            $headersStringParts[] = $key . ':' . $value;
        }

        $headersString = implode("\n", $headersStringParts);

        $message = trim(sprintf(
            "HTTP/1.1 %s\n%s\n\n%s",
            $statusCode,
            $headersString,
            $body
        ));

        return Response::fromMessage($message);
    }

    /**
     * @param mixed $data
     *
     * @return Response
     */
    public static function createJsonResponse($data)
    {
        return self::create(
            200,
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($data)
        );
    }
}
