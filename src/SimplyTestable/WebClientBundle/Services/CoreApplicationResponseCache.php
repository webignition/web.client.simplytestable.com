<?php

namespace SimplyTestable\WebClientBundle\Services;

use GuzzleHttp\Message\MessageFactory;
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

        if (!isset($this->cache[$requestHash])) {
            return null;
        }

        $serializedResponse = $this->cache[$requestHash];

        $messageFactory = new MessageFactory();

        return $messageFactory->fromMessage($serializedResponse);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface|bool
     */
    public function set(RequestInterface $request, ResponseInterface $response)
    {
        if ('GET' === $request->getMethod()) {
            $requestHash = $this->createRequestHash($request);

            $this->cache[$requestHash] = (string)$response;

            $messageFactory = new MessageFactory();

            $response = $messageFactory->fromMessage($this->cache[$requestHash]);
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
            'url' => $request->getUrl(),
            'headers' => $request->getHeaders(),
        ]));
    }
}
