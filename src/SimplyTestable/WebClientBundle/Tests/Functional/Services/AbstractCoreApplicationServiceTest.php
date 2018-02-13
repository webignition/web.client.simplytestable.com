<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

abstract class AbstractCoreApplicationServiceTest extends AbstractBaseTestCase
{
    /**
     * @var HistoryPlugin
     */
    private $httpHistoryPlugin;

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

        $this->httpHistoryPlugin = new HistoryPlugin();
        $this->coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $httpClient = $this->coreApplicationHttpClient->getHttpClient();
        $httpClient->addSubscriber($this->httpHistoryPlugin);
    }

    /**
     * @return RequestInterface
     */
    protected function getLastRequest()
    {
        return $this->httpHistoryPlugin->getLastRequest();
    }

    /**
     * @return array
     */
    protected function getRequestedUrls()
    {
        $requestedUrls = [];

        foreach ($this->httpHistoryPlugin->getAll() as $httpTransaction) {
            /* @var Request $request */
            $request = $httpTransaction['request'];

            $requestedUrls[] = $request->getUrl();
        }

        return $requestedUrls;
    }
}
