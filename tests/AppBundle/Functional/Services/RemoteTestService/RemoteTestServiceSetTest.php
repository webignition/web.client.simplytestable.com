<?php

namespace Tests\AppBundle\Functional\Services\RemoteTestService;

use AppBundle\Model\RemoteTest\RemoteTest;

class RemoteTestServiceSetTest extends AbstractRemoteTestServiceTest
{
    public function testSet()
    {
        $remoteTest = new RemoteTest([]);

        $this->remoteTestService->set($remoteTest);

        $this->assertEquals($remoteTest, $this->remoteTestService->get());
    }
}
