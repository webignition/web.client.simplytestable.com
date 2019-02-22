<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Test\Test;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;

class TaskServiceGetRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getRemoteTaskIdsDataProvider
     */
    public function testGetRemoteTaskIds(
        array $httpFixtures,
        array $testValues,
        array $expectedRemoteTaskIds,
        array $expectedRequestUrls
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

        $remoteTaskIds = $this->taskService->getRemoteTaskIds($test);

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
        $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrls());
    }

    public function getRemoteTaskIdsDataProvider(): array
    {
        return [
            'state: new' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_STARTING,
                ],
                'expectedRemoteTaskIds' => [],
                'expectedRequestUrls' => [],
            ],
            'state: preparing' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_PREPARING,
                ],
                'expectedRemoteTaskIds' => [],
                'expectedRequestUrls' => [],
            ],
            'state: completed' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        2, 3,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_COMPLETED,
                ],
                'expectedRemoteTaskIds' => [2, 3],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                ],
            ],
            'already set' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_COMPLETED,
                    TestFactory::KEY_TASK_IDS => '4,5'
                ],
                'expectedRemoteTaskIds' => [4, 5],
                'expectedRequestUrls' => [],
            ],
        ];
    }
}
