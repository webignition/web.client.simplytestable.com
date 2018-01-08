<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceLockUnlockTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

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

        $this->test = new Test();
        $this->test->setTestId(1);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($this->test);

        $this->httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->getHttpClientService();
        $httpClientService->get()->addSubscriber($this->httpHistoryPlugin);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->remoteTestService->setUser($this->user);
    }

    public function testLock()
    {
        $this->remoteTestService->lock();

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-private/',
            $lastRequest->getUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock();

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-public/',
            $lastRequest->getUrl()
        );
    }
}
