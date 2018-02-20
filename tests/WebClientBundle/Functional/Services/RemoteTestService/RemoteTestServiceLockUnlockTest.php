<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceLockUnlockTest extends AbstractRemoteTestServiceTest
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

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testLock()
    {
        $this->remoteTestService->lock();
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-private/',
            $this->getLastRequest()->getUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock();
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-public/',
            $this->getLastRequest()->getUrl()
        );
    }
}