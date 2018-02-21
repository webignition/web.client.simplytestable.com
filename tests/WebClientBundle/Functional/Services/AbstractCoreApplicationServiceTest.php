<?php

namespace Tests\WebClientBundle\Functional\Services;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractCoreApplicationServiceTest extends AbstractBaseTestCase
{
    /**
     * @var HttpHistorySubscriber
     */
    private $httpHistory;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpHistory = new HttpHistorySubscriber();

        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $this->userManager = $this->container->get(UserManager::class);

        $httpClient = $coreApplicationHttpClient->getHttpClient();
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
