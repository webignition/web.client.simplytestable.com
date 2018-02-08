<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;

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
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetUnretrievedRemoteTaskIds(
        array $httpFixtures,
        array $testValues,
        $limit,
        array $expectedUnretrievedRemoteTaskIds
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $testFactory = new TestFactory($this->container);

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
