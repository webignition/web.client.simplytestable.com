<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractCoreApplicationServiceTest extends BaseSimplyTestableTestCase
{
    /**
     * @var HistoryPlugin
     */
    private $httpHistoryPlugin;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $httpClientService->get()->addSubscriber($this->httpHistoryPlugin);
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
