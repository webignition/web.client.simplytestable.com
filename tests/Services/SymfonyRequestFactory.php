<?php

namespace App\Tests\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SymfonyRequestFactory
{
    /**
     * Creates a new request as a duplicate of a request with 'if-modified-since' and 'if-none-match' headers
     * set based on the response to the initial request
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Request
     *
     * @throws \Exception
     */
    public function createFollowUpRequest(Request $request, Response $response): Request
    {
        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newRequest->headers->set('if-none-match', $response->getEtag());

        return $newRequest;
    }
}
