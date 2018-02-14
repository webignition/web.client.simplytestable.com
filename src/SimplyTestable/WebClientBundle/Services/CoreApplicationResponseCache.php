<?php

namespace SimplyTestable\WebClientBundle\Services;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

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

        return isset($this->cache[$requestHash])
            ? $this->cache[$requestHash]
            : null;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function set(RequestInterface $request, ResponseInterface $response)
    {
        if ('GET' !== $request->getMethod()) {
            return false;
        }

        $requestHash = $this->createRequestHash($request);

        $this->cache[$requestHash] = $response;

        return true;
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
            'url' => $request->getUrl(),
            'headers' => $request->getHeaders(),
        ]));
    }
}
