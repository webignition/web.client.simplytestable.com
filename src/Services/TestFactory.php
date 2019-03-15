<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;

class TestFactory
{
    public function create(TestEntity $entity, RemoteTest $remoteTest): TestModel
    {
        return new TestModel(
            $entity,
            $remoteTest->getWebsite(),
            $remoteTest->getUser(),
            $remoteTest->getState(),
            $remoteTest->getType(),
            $remoteTest->getTaskTypes(),
            $remoteTest->getUrlCount(),
            $entity->getErrorCount(),
            $entity->getWarningCount(),
            $remoteTest->getTaskCount(),
            $remoteTest->getErroredTaskCount(),
            $remoteTest->getCancelledTaskCount(),
            $remoteTest->getEncodedParameters()
        );
    }
}
