<?php

namespace SimplyTestable\WebClientBundle\Services;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7;

class CoreApplicationResponseCache
{
    /**
     * @var ResponseInterface[]
     */
    private $cache;

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface|null
     */
    public function get(RequestInterface $request)
    {
        $requestHash = $this->createRequestHash($request);

        if (empty($requestHash)) {
            return null;
        }

        if (!isset($this->cache[$requestHash])) {
            return null;
        }

        return Psr7\parse_response($this->cache[$requestHash]);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function set(RequestInterface $request, ResponseInterface $response)
    {
        if ('GET' === $request->getMethod()) {
            $requestHash = $this->createRequestHash($request);

            $this->cache[$requestHash] = Psr7\str($response);

            $response = Psr7\parse_response($this->cache[$requestHash]);
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    private function createRequestHash(RequestInterface $request)
    {
        if ('GET' !== $request->getMethod()) {
            return null;
        }

        return md5(json_encode([
            'url' => (string)$request->getUri(),
            'headers' => $request->getHeaders(),
        ]));
    }
}
