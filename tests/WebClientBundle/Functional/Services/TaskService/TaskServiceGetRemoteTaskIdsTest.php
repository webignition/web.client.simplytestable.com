<?php

namespace Tests\WebClientBundle\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\TestFactory;

class TaskServiceGetRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getRemoteTaskIdsDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param array $expectedRemoteTaskIds
     * @param string[] $expectedRequestUrls
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetRemoteTaskIds(
        array $httpFixtures,
        array $testValues,
        array $expectedRemoteTaskIds,
        array $expectedRequestUrls
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory($this->container);

        $test = $testFactory->create($testValues);

        $remoteTaskIds = $this->taskService->getRemoteTaskIds($test);

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
        $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrls());
    }

    /**
     * @return array
     */
    public function getRemoteTaskIdsDataProvider()
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
