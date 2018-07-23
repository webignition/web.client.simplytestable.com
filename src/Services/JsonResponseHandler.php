<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;
use App\Exception\InvalidContentTypeException;

class JsonResponseHandler
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     *
     * @throws InvalidContentTypeException
     */
    public function handle(ResponseInterface $response)
    {
        $responseContentType = $response->getHeaderLine('content-type');

        if (self::APPLICATION_JSON_CONTENT_TYPE !== $responseContentType) {
            throw new InvalidContentTypeException($responseContentType);
        }

        $responseData = json_decode($response->getBody()->getContents(), true);

        return $responseData;
    }
}
