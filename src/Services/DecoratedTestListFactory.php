<?php

namespace App\Services;

use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;
use App\Model\Test\DecoratedTest;

class DecoratedTestListFactory
{
    private $testService;
    private $testFactory;

    public function __construct(TestService $testService, TestFactory $testFactory)
    {
        $this->testService = $testService;
        $this->testFactory = $testFactory;
    }

    public function create(RemoteTestList $remoteTestList): DecoratedTestList
    {
        $decoratedTests = [];

        foreach ($remoteTestList->get() as $testData) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testData['remote_test'];
            $test = $this->testService->get($remoteTest->getId());

            $testModel = $this->testFactory->create($test, $remoteTest, [
                'website' => $remoteTest->getWebsite(),
                'user' => $remoteTest->getUser(),
                'state' => $remoteTest->getState(),
                'type' => $remoteTest->getType(),
                'url_count' => $remoteTest->getUrlCount(),
                'task_count' => $remoteTest->getTaskCount(),
                'errored_task_count' => $remoteTest->getErroredTaskCount(),
                'cancelled_task_count' => $remoteTest->getCancelledTaskCount(),
                'parameters' => $remoteTest->getEncodedParameters(),
                'amendments' => $remoteTest->getAmmendments(),
                'crawl' => $remoteTest->getCrawl(),
                'task_types' => $remoteTest->getTaskTypes(),
                'task_count_by_state' => $remoteTest->getRawTaskCountByState(),
            ]);

            $decoratedTests[] = new DecoratedTest($testModel);
        }

        return new DecoratedTestList(
            $decoratedTests,
            $remoteTestList->getMaxResults(),
            $remoteTestList->getOffset(),
            $remoteTestList->getLimit()
        );
    }
}
