<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Services\TestCompletionPercentCalculator;
use App\Services\TestFactory;
use App\Services\TestTaskCountByStateNormaliser;
use App\Tests\Services\ObjectReflector;

class DecoratedTestListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(array $decoratedTests, int $maxResults, int $offset, int $limit, array $expectedTests)
    {
        $decoratedTestList = new DecoratedTestList($decoratedTests, $maxResults, $offset, $limit);

        $this->assertSame($expectedTests, ObjectReflector::getProperty($decoratedTestList, 'tests'));
        $this->assertEquals($maxResults, $decoratedTestList->getMaxResults());
        $this->assertEquals($offset, $decoratedTestList->getOffset());
        $this->assertEquals($limit, $decoratedTestList->getLimit());
    }

    public function createDataProvider(): array
    {
        $testId = 7;

        $entity = TestEntity::create($testId);
        $remoteTest = new RemoteTest([]);

        $testFactory = new TestFactory(
            new TestCompletionPercentCalculator(),
            new TestTaskCountByStateNormaliser()
        );
        $test = $testFactory->create($entity, $remoteTest, []);

        $decoratedTest = new DecoratedTest($test);

        return [
            'no remote tests, default maxResults, offset, limit' => [
                'remoteTests' => [],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [],
            ],
            'no remote tests, non-default maxResults, offset, limit' => [
                'remoteTests' => [],
                'maxResults' => 3,
                'offset' => 4,
                'limit' => 5,
                'expectedTests' => [],
            ],
            'no valid decorated tests' => [
                'remoteTests' => [
                    1,
                    true,
                    new \stdClass(),
                ],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [],
            ],
            'single valid decorated test' => [
                'remoteTests' => [
                    1,
                    true,
                    new \stdClass(),
                    $decoratedTest,
                ],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [
                    $decoratedTest
                ],
            ],
        ];
    }

    public function testIterator()
    {
        $decoratedTests = [
            new DecoratedTest(\Mockery::mock(TestModel::class)),
            new DecoratedTest(\Mockery::mock(TestModel::class)),
            new DecoratedTest(\Mockery::mock(TestModel::class)),
        ];

        $decoratedTestList = new DecoratedTestList($decoratedTests, 3, 0, 3);

        foreach ($decoratedTestList as $testIndex => $decoratedTest) {
            $this->assertSame($decoratedTests[$testIndex], $decoratedTest);
        }
    }

    /**
     * @dataProvider getLengthDataProvider
     */
    public function testGetLength(array $decoratedTests, int $expectedLength)
    {
        $decoratedTestList = new DecoratedTestList($decoratedTests, 0, 0, 0);

        $this->assertEquals($expectedLength, $decoratedTestList->getLength());
    }

    public function getLengthDataProvider(): array
    {
        return [
            'no tests' => [
                'decoratedTests' => [],
                'expectedLength' => 0,
            ],
            'one test' => [
                'decoratedTests' => [
                    new DecoratedTest(\Mockery::mock(TestModel::class)),
                ],
                'expectedLength' => 1,
            ],
            'three tests' => [
                'decoratedTests' => [
                    new DecoratedTest(\Mockery::mock(TestModel::class)),
                    new DecoratedTest(\Mockery::mock(TestModel::class)),
                    new DecoratedTest(\Mockery::mock(TestModel::class)),
                ],
                'expectedLength' => 3,
            ],
        ];
    }

    /**
     * @dataProvider getPageIndexDataProvider
     */
    public function testGetPageIndex(int $offset, int $limit, int $expectedPageIndex)
    {
        $decoratedTestList = new DecoratedTestList([], 0, $offset, $limit);

        $this->assertEquals($expectedPageIndex, $decoratedTestList->getPageIndex());
    }

    public function getPageIndexDataProvider(): array
    {
        return [
            'offset=0, limit=0' => [
                'offset' => 0,
                'limit' => 0,
                'expectedPageIndex' => 0,
            ],
            'offset=0, limit=1' => [
                'offset' => 0,
                'limit' => 1,
                'expectedPageIndex' => 0,
            ],
            'offset=1, limit=1' => [
                'offset' => 1,
                'limit' => 1,
                'expectedPageIndex' => 1,
            ],
            'offset=2, limit=1' => [
                'offset' => 2,
                'limit' => 1,
                'expectedPageIndex' => 2,
            ],
            'offset=100, limit=10' => [
                'offset' => 100,
                'limit' => 10,
                'expectedPageIndex' => 10,
            ],
        ];
    }

    /**
     * @dataProvider getPageNumberDataProvider
     */
    public function testGetPageNumber(int $offset, int $limit, int $expectedPageNumber)
    {
        $decoratedTestList = new DecoratedTestList([], 0, $offset, $limit);

        $this->assertEquals($expectedPageNumber, $decoratedTestList->getPageNumber());
    }

    public function getPageNumberDataProvider(): array
    {
        return [
            'offset=0, limit=0' => [
                'offset' => 0,
                'limit' => 0,
                'expectedPageNumber' => 1,
            ],
            'offset=0, limit=1' => [
                'offset' => 0,
                'limit' => 1,
                'expectedPageNumber' => 1,
            ],
            'offset=1, limit=1' => [
                'offset' => 1,
                'limit' => 1,
                'expectedPageNumber' => 2,
            ],
            'offset=2, limit=1' => [
                'offset' => 2,
                'limit' => 1,
                'expectedPageNumber' => 3,
            ],
            'offset=100, limit=10' => [
                'offset' => 100,
                'limit' => 10,
                'expectedPageNumber' => 11,
            ],
        ];
    }

    /**
     * @dataProvider getPageCountDataProvider
     */
    public function testGetPageCount(int $maxResults, int $limit, int $expectedPageCount)
    {
        $decoratedTestList = new DecoratedTestList([], $maxResults, 0, $limit);

        $this->assertEquals($expectedPageCount, $decoratedTestList->getPageCount());
    }

    public function getPageCountDataProvider(): array
    {
        return [
            'maxResults=0, limit=0' => [
                'maxResults' => 0,
                'limit' => 0,
                'expectedPageCount' => 0,
            ],
            'maxResults=10, limit=1' => [
                'maxResults' => 10,
                'limit' => 1,
                'expectedPageCount' => 10,
            ],
            'maxResults=50, limit=10' => [
                'maxResults' => 50,
                'limit' => 10,
                'expectedPageCount' => 5,
            ],
            'maxResults=100, limit=10' => [
                'maxResults' => 100,
                'limit' => 10,
                'expectedPageCount' => 10,
            ],
            'maxResults=101, limit=10' => [
                'maxResults' => 101,
                'limit' => 10,
                'expectedPageCount' => 11,
            ],
        ];
    }

    /**
     * @dataProvider getPageCollectionIndexDataProvider
     */
    public function testGetPageCollectionIndex(int $offset, int $limit, int $expectedPageCollectionIndex)
    {
        $decoratedTestList = new DecoratedTestList([], 0, $offset, $limit);

        $this->assertEquals($expectedPageCollectionIndex, $decoratedTestList->getPageCollectionIndex());
    }

    public function getPageCollectionIndexDataProvider(): array
    {
        return [
            'offset=0, limit=0' => [
                'offset' => 0,
                'limit' => 0,
                'expectedPageCollectionIndex' => 0,
            ],
            'offset=0, limit=1' => [
                'offset' => 0,
                'limit' => 1,
                'expectedPageCollectionIndex' => 0,
            ],
            'offset=1, limit=1' => [
                'offset' => 1,
                'limit' => 1,
                'expectedPageCollectionIndex' => 0,
            ],
            'offset=2, limit=1' => [
                'offset' => 2,
                'limit' => 1,
                'expectedPageCollectionIndex' => 0,
            ],
            'offset=100, limit=10' => [
                'offset' => 100,
                'limit' => 10,
                'expectedPageCollectionIndex' => 1,
            ],
            'offset=1000, limit=10' => [
                'offset' => 1000,
                'limit' => 10,
                'expectedPageCollectionIndex' => 10,
            ],
        ];
    }

    /**
     * @dataProvider getPageNumbersDataProviders
     */
    public function testGetPageNumbers(int $maxResults, int $offset, int $limit, array $expectedPageNumbers)
    {
        $decoratedTestList = new DecoratedTestList([], $maxResults, $offset, $limit);

        $this->assertEquals($expectedPageNumbers, $decoratedTestList->getPageNumbers());
    }

    public function getPageNumbersDataProviders(): array
    {
        return [
            'maxResults less than limit' => [
                'maxResults' => 3,
                'offset' => 0,
                'limit' => 4,
                'expectedPageNumbers' => [],
            ],
            'maxResults equals limit' => [
                'maxResults' => 10,
                'offset' => 0,
                'limit' => 10,
                'expectedPageNumbers' => [],
            ],
            'maxResults=10, offset=0, limit=4' => [
                'maxResults' => 10,
                'offset' => 0,
                'limit' => 4,
                'expectedPageNumbers' => [1, 2, 3],
            ],
            'maxResults=100, offset=0, limit=10' => [
                'maxResults' => 100,
                'offset' => 0,
                'limit' => 10,
                'expectedPageNumbers' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            'maxResults=100, offset=20, limit=10' => [
                'maxResults' => 200,
                'offset' => 20,
                'limit' => 10,
                'expectedPageNumbers' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            'maxResults=100, offset=90, limit=10' => [
                'maxResults' => 200,
                'offset' => 90,
                'limit' => 10,
                'expectedPageNumbers' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            'maxResults=100, offset=100, limit=10' => [
                'maxResults' => 200,
                'offset' => 100,
                'limit' => 10,
                'expectedPageNumbers' => [11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            ],
        ];
    }
}
