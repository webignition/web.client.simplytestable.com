<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

class RemoteTestServiceSetTest extends AbstractRemoteTestServiceTest
{
    public function testSet()
    {
        $remoteTest = new RemoteTest([]);

        $this->remoteTestService->set($remoteTest);

        $this->assertEquals($remoteTest, $this->remoteTestService->get());
    }
}
