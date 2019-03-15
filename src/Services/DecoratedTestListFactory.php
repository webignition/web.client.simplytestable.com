<?php

namespace App\Services;

use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;
use App\Model\Test\DecoratedTest;

class DecoratedTestListFactory
{
    private $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function create(RemoteTestList $remoteTestList): DecoratedTestList
    {
        $decoratedTests = [];

        foreach ($remoteTestList->get() as $testData) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testData['remote_test'];
            $test = $this->testService->get($remoteTest->getId());

            $decoratedTests[] = new DecoratedTest($test, $remoteTest);
        }

        return new DecoratedTestList(
            $decoratedTests,
            $remoteTestList->getMaxResults(),
            $remoteTestList->getOffset(),
            $remoteTestList->getLimit()
        );
    }
}
