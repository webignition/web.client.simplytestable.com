<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class RemoteTestServiceSetTest extends AbstractRemoteTestServiceTest
{
    public function testSet()
    {
        $remoteTestData = new \stdClass();
        $remoteTest = new RemoteTest($remoteTestData);

        $this->remoteTestService->set($remoteTest);

        $this->assertEquals($remoteTest, $this->remoteTestService->get());
    }
}
