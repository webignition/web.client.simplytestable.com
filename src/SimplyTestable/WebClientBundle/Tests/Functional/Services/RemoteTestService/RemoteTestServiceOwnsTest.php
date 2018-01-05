<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceOwnsTest extends AbstractRemoteTestServiceTest
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

    public function testOwnsDirectOwner()
    {
        $this->test->setUser($this->user->getUsername());
        $this->remoteTestService->setUser($this->user);

        $this->assertTrue($this->remoteTestService->owns());
    }

    public function testOwnsRemoteHttpFailure()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 503'),
        ]);

        $this->remoteTestService->setUser($this->user);

        $this->setExpectedException(WebResourceException::class);

        $this->remoteTestService->owns();
    }

    public function testOwnsRemoteHttp403()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 403'),
        ]);

        $this->remoteTestService->setUser($this->user);

        $this->assertFalse($this->remoteTestService->owns());
    }

    public function testOwnsOwnersDoesNotContain()
    {
        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                    'id' => 1,
                    'owners' => [
                        'foo@example.com',
                        'bar@example.com',
                    ],
                ])),
        ]);

        $this->remoteTestService->setUser($this->user);

        $this->assertFalse($this->remoteTestService->owns());
    }

    public function testOwnsOwnersContains()
    {
        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                'id' => 1,
                'owners' => [
                    $this->user->getUsername(),
                    'bar@example.com',
                ],
            ])),
        ]);

        $this->remoteTestService->setUser($this->user);

        $this->assertTrue($this->remoteTestService->owns());
    }
}
