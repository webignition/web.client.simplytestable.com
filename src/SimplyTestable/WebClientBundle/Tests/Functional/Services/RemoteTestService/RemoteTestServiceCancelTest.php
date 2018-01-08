<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\WebResource\WebResource;

class RemoteTestServiceCancelTest extends AbstractRemoteTestServiceTest
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
    }

    public function testCancel()
    {
        $this->setHttpFixtures([
           Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->remoteTestService->setUser($this->user);

        $response = $this->remoteTestService->cancel();

        $this->assertInstanceOf(WebResource::class, $response);

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/cancel/',
            $lastRequest->getUrl()
        );
    }

    public function testCancelByTestProperties()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->remoteTestService->setUser($this->user);

        $response = $this->remoteTestService->cancelByTestProperties(2, 'http://foo.example.com');

        $this->assertInstanceOf(WebResource::class, $response);

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Ffoo.example.com/2/cancel/',
            $lastRequest->getUrl()
        );
    }
}
