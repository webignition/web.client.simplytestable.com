<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;
use App\Services\TestFactory;

class TestFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestFactory
     */
    private $testFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->testFactory = new TestFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(TestEntity $entity, RemoteTest $remoteTest)
    {
        $test = $this->testFactory->create($entity, $remoteTest);

        $this->assertInstanceOf(TestModel::class, $test);
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'entity' => TestEntity::create(1),
                'remoteTest' => new RemoteTest([
                    'id' => 1,
                    'website' => 'http://example.com/',
                    'user' => 'user@example.com',
                    'state' => TestEntity::STATE_COMPLETED,
                    'type' => TestEntity::TYPE_FULL_SITE,
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ],
                    'url_count' => 1,
                ]),
                'expectedTest' => new TestModel(
                    TestEntity::create(1),
                    'http://example.com/',
                    'user@example.com',
                    TestEntity::STATE_COMPLETED,
                    TestEntity::TYPE_FULL_SITE,
                    [],
                    1,
                    2,
                    3
                ),
            ],
        ];
    }
}
