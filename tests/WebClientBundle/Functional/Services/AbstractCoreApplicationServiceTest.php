<?php

namespace Tests\WebClientBundle\Functional\Services;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractCoreApplicationServiceTest extends AbstractBaseTestCase
{
    /**
     * @var HttpHistorySubscriber
     */
    private $httpHistory;

    /**
     * @var CoreApplicationHttpClient
     */
    protected $coreApplicationHttpClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpHistory = new HttpHistorySubscriber();

        $this->coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $httpClient = $this->coreApplicationHttpClient->getHttpClient();
        $httpClient->getEmitter()->attach($this->httpHistory);
    }

    /**
     * @return RequestInterface
     */
    protected function getLastRequest()
    {
        return $this->httpHistory->getLastRequest();
    }

    /**
     * @return array
     */
    protected function getRequestedUrls()
    {
        $requestedUrls = [];

        foreach ($this->httpHistory as $httpTransaction) {
            /* @var RequestInterface $request */
            $request = $httpTransaction['request'];

            $requestedUrls[] = $request->getUrl();
        }

        return $requestedUrls;
    }
}
