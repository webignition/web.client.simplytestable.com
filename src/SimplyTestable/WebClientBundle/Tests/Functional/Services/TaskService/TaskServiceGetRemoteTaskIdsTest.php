<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;

class TaskServiceGetRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getCollectionDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param array $expectedRemoteTaskIds
     */
    public function testGetCollection(
        array $httpFixtures,
        array $testValues,
        array $expectedRemoteTaskIds
    ) {
        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $testFactory = new TestFactory($this->container);

        $test = $testFactory->create($testValues);

        $remoteTaskIds = $this->taskService->getRemoteTaskIds($test);

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    /**
     * @return array
     */
    public function getCollectionDataProvider()
    {
        return [
            'state: new' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_STARTING,
                ],
                'expectedRemoteTaskIds' => [],
            ],
            'state: preparing' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_PREPARING,
                ],
                'expectedRemoteTaskIds' => [],
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
            ],
            'already set' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_STATE => Test::STATE_COMPLETED,
                    TestFactory::KEY_TASK_IDS => '4,5'
                ],
                'expectedRemoteTaskIds' => [4, 5],
            ],
        ];
    }
}
