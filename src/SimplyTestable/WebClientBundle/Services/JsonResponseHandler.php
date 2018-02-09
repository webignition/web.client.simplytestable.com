<?php

namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;

class JsonResponseHandler
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';

    /**
     * @param Response $response
     *
     * @return mixed
     *
     * @throws InvalidContentTypeException
     */
    public function handle(Response $response)
    {
        if (self::APPLICATION_JSON_CONTENT_TYPE !== $response->getContentType()) {
            throw new InvalidContentTypeException($response->getContentType());
        }

        $responseData = json_decode($response->getBody(true), true);

        return $responseData;
    }
}
