<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceAuthenticateTest extends AbstractRemoteTestServiceTest
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

    public function testAuthenticateDirectOwner()
    {
        $this->test->setUser($this->user->getUsername());
        $this->remoteTestService->setUser($this->user);

        $this->assertTrue($this->remoteTestService->authenticate());
    }

    /**
     * @dataProvider authenticateViaIsPublicDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedAuthenticateResult
     *
     * @throws WebResourceException
     */
    public function testAuthenticateViaIsPublic($httpFixtures, $expectedAuthenticateResult)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->remoteTestService->setUser($this->user);

        $this->assertEquals($expectedAuthenticateResult, $this->remoteTestService->authenticate());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            $lastRequest->getUrl()
        );
    }

    /**
     * @return array
     */
    public function authenticateViaIsPublicDataProvider()
    {
        return [
            'is not public' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'is_public' => false,
                    ]))
                ],
                'expectedAuthenticateResult' => false,
            ],
            'is public' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'is_public' => true,
                    ]))
                ],
                'expectedAuthenticateResult' => true,
            ],
        ];
    }
}
