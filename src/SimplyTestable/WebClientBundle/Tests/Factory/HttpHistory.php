<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Services\HttpClientService;

class HttpHistory
{
    /**
     * @var HistoryPlugin
     */
    private $historyPlugin;

    /**
     * @param HttpClientService $httpClientService
     */
    public function __construct(HttpClientService $httpClientService)
    {
        $httpClient = $httpClientService->get();

        $this->historyPlugin = new HistoryPlugin();
        $httpClient->addSubscriber($this->historyPlugin);
    }

    /**
     * @return RequestInterface
     */
    public function getLastRequest()
    {
        return $this->historyPlugin->getLastRequest();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->historyPlugin->count();
    }

    /**
     * @return string[]
     */
    public function getRequestUrls()
    {
        $requestUrls = [];

        foreach ($this->historyPlugin->getAll() as $httpTransaction) {
            /* @var RequestInterface $request */
            $request = $httpTransaction['request'];

            $requestUrls[] = $request->getUrl();
        }

        return $requestUrls;
    }
}
