<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceRetestTest extends AbstractRemoteTestServiceTest
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

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->remoteTestService->setUser($this->user);
    }

    public function testRetest()
    {
        $this->remoteTestService->retest($this->test->getTestId(), $this->test->getWebsite());
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/re-test/',
            $this->getLastRequest()->getUrl()
        );
    }
}
