<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;

class TaskServiceGetUnretrievedRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getUnretrievedRemoteTaskIdsDataProvider
     */
    public function testGetUnretrievedRemoteTaskIds(
        string $testTaskIds,
        int $limit,
        array $expectedUnretrievedRemoteTaskIds
    ) {
        $testId = 1;

        $test = Test::create($testId);
        $test->setTaskIdCollection($testTaskIds);

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($test);
        $entityManager->flush();

        $unretrievedRemoteTaskIds = $this->taskService->getUnretrievedRemoteTaskIds($test, $limit);

        $this->assertEquals($expectedUnretrievedRemoteTaskIds, $unretrievedRemoteTaskIds);
    }

    public function getUnretrievedRemoteTaskIdsDataProvider(): array
    {
        return [
            'none retrieved, limit not reached' => [
                'testTaskIds' => '2,3',
                'limit' => 10,
                'expectedUnretrievedRemoteTaskIds' => [2, 3],
            ],
            'none retrieved, limit reached' => [
                'testTaskIds' => '2,3,4,5',
                'limit' => 3,
                'expectedUnretrievedRemoteTaskIds' => [2, 3, 4],
            ],
            'all retrieved, limit reached' => [
                'testTaskIds' => '2,3,4,5',
                'limit' => 3,
                'expectedUnretrievedRemoteTaskIds' => [2, 3, 4],
            ],
        ];
    }
}
