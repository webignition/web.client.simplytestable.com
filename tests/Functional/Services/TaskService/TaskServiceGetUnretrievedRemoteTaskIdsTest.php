<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;

class TaskServiceGetUnretrievedRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getUnretrievedRemoteTaskIdsDataProvider
     */
    public function testGetUnretrievedRemoteTaskIds(
        array $httpFixtures,
        array $testValues,
        int $limit,
        array $expectedUnretrievedRemoteTaskIds
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

        $unretrievedRemoteTaskIds = $this->taskService->getUnretrievedRemoteTaskIds($test, $limit);

        $this->assertEquals($expectedUnretrievedRemoteTaskIds, $unretrievedRemoteTaskIds);
    }

    public function getUnretrievedRemoteTaskIdsDataProvider(): array
    {
        return [
            'none retrieved, limit not reached' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        2, 3,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'limit' => 10,
                'expectedUnretrievedRemoteTaskIds' => [2, 3],
            ],
            'none retrieved, limit reached' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        2, 3, 4, 5
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'limit' => 3,
                'expectedUnretrievedRemoteTaskIds' => [2, 3, 4],
            ],
            'all retrieved, limit not reached' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_TASK_IDS => '2, 3'
                ],
                'limit' => 10,
                'expectedUnretrievedRemoteTaskIds' => [2, 3,],
            ],
            'all retrieved, limit reached' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_TASK_IDS => '2, 3, 4, 5'
                ],
                'limit' => 3,
                'expectedUnretrievedRemoteTaskIds' => [2, 3, 4],
            ],
        ];
    }
}
