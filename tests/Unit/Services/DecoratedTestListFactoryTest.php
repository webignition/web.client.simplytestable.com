<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Test as TestEntity;
use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;
use App\Model\Test\DecoratedTest;
use App\Services\DecoratedTestListFactory;
use App\Services\RemoteTestCompletionPercentCalculator;
use App\Services\TestFactory;
use App\Services\TestService;
use App\Tests\Services\ObjectReflector;
use Mockery\MockInterface;

class DecoratedTestListFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(TestService $testService, RemoteTestList $remoteTestList, array $expectedDecoratedTests)
    {
        $testFactory = new TestFactory(new RemoteTestCompletionPercentCalculator());
        $decoratedTestListFactory = new DecoratedTestListFactory($testService, $testFactory);

        $decoratedTestList = $decoratedTestListFactory->create($remoteTestList);

        $this->assertInstanceOf(DecoratedTestList::class, $decoratedTestList);
        $this->assertEquals($remoteTestList->getMaxResults(), $decoratedTestList->getMaxResults());
        $this->assertEquals($remoteTestList->getOffset(), $decoratedTestList->getOffset());
        $this->assertEquals($remoteTestList->getLimit(), $decoratedTestList->getLimit());

        foreach ($decoratedTestList as $decoratedTestIndex => $decoratedTest) {
            /* @var DecoratedTest $expectedDecoratedTest */
            $expectedDecoratedTest = $expectedDecoratedTests[$decoratedTestIndex];

            $this->assertInstanceOf(DecoratedTest::class, $decoratedTest);
            $this->assertEquals(
                ObjectReflector::getProperty($expectedDecoratedTest, 'test'),
                ObjectReflector::getProperty($decoratedTest, 'test')
            );
            $this->assertEquals(
                ObjectReflector::getProperty($expectedDecoratedTest, 'remoteTest'),
                ObjectReflector::getProperty($decoratedTest, 'remoteTest')
            );
        }
    }

    public function createDataProvider(): array
    {
        $entity1 = TestEntity::create(1);

        $remoteTest1 = new RemoteTest([
            'id' => 1,
            'website' => 'http://example.com/1/',
        ]);

        $testFactory = new TestFactory(new RemoteTestCompletionPercentCalculator());
        $test1 = $testFactory->create($entity1, $remoteTest1, []);

        return [
            'empty remote test list' => [
                'testService' => $this->createTestService(),
                'remoteTestList' => new RemoteTestList([], 100, 0, 10),
                'expectedDecoratedTests' => [],
            ],
            'single remote test' => [
                'testService' => $this->createTestService([
                    [
                        'expectedId' => 1,
                        'test' => $entity1,
                    ],
                ]),
                'remoteTestList' => new RemoteTestList(
                    [
                        $remoteTest1,
                    ],
                    100,
                    0,
                    10
                ),
                'expectedDecoratedTests' => [
                    new DecoratedTest($test1),
                ],
            ],
        ];
    }

    /**
     * @return TestService|MockInterface
     */
    private function createTestService(array $getCalls = [])
    {
        $testService = \Mockery::mock(TestService::class);

        foreach ($getCalls as $getCall) {
            $testService
                ->shouldReceive('get')
                ->with($getCall['expectedId'])
                ->andReturn($getCall['test']);
        }

        return $testService;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
