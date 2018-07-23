<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Model\RemoteTest\RemoteTest;

class RemoteTestServiceSetTest extends AbstractRemoteTestServiceTest
{
    public function testSet()
    {
        $remoteTest = new RemoteTest([]);

        $this->remoteTestService->set($remoteTest);

        $this->assertEquals($remoteTest, $this->remoteTestService->get());
    }
}
