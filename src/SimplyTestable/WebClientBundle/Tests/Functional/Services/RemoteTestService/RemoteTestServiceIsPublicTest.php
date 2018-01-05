<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceIsPublicTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

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
    }

    /**
     * @dataProvider isPublicDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedAuthenticateResult
     *
     * @throws WebResourceException
     */
    public function testIsPublic($httpFixtures, $expectedAuthenticateResult)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->remoteTestService->setUser($this->user);

        $this->assertEquals($expectedAuthenticateResult, $this->remoteTestService->isPublic());
    }

    /**
     * @return array
     */
    public function isPublicDataProvider()
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
