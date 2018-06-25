<?php

namespace SimplyTestable\WebClientBundle\Services;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientFactory
{
    const MIDDLEWARE_RETRY_KEY = 'retry';
    const MAX_RETRIES = 5;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var HandlerStack
     */
    protected $handlerStack;

    public function __construct()
    {
        $this->handlerStack = HandlerStack::create($this->createInitialHandler());
        $this->httpClient = $this->create();
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return HttpClient
     */
    private function create()
    {
        $this->handlerStack->push(Middleware::retry($this->createRetryDecider()), self::MIDDLEWARE_RETRY_KEY);

        return new HttpClient([
            'verify' => false,
            'handler' => $this->handlerStack,
            'max_retries' => self::MAX_RETRIES,
        ]);
    }

    /**
     * @return callable|null
     */
    protected function createInitialHandler()
    {
        return null;
    }

    /**
     * @return \Closure
     */
    private function createRetryDecider()
    {
        return function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null,
            GuzzleException $exception = null
        ) {
            if (in_array($request->getMethod(), ['POST'])) {
                return false;
            }

            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response instanceof ResponseInterface && $response->getStatusCode() >= 500) {
                return true;
            }

            return false;
        };
    }
}
