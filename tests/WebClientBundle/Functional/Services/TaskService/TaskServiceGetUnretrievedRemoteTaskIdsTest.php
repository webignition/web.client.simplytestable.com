<?php

namespace Tests\WebClientBundle\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\TestFactory;

class TaskServiceGetUnretrievedRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getUnretrievedRemoteTaskIdsDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param int $limit
     * @param int[] $expectedUnretrievedRemoteTaskIds
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetUnretrievedRemoteTaskIds(
        array $httpFixtures,
        array $testValues,
        $limit,
        array $expectedUnretrievedRemoteTaskIds
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

        $unretrievedRemoteTaskIds = $this->taskService->getUnretrievedRemoteTaskIds($test, $limit);

        $this->assertEquals($expectedUnretrievedRemoteTaskIds, $unretrievedRemoteTaskIds);
    }

    /**
     * @return array
     */
    public function getUnretrievedRemoteTaskIdsDataProvider()
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
