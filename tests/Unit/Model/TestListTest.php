<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Model\Test;
use App\Model\TestList;
use App\Tests\Factory\TestModelFactory;
use App\Tests\Services\ObjectReflector;

class TestListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(array $tests, int $maxResults, int $offset, int $limit, array $expectedTests)
    {
        $testList = new TestList($tests, $maxResults, $offset, $limit);

        $this->assertEquals($expectedTests, ObjectReflector::getProperty($testList, 'tests'));
        $this->assertEquals($maxResults, $testList->getMaxResults());
        $this->assertEquals($offset, ObjectReflector::getProperty($testList, 'offset'));
        $this->assertEquals($limit, ObjectReflector::getProperty($testList, 'limit'));
    }

    public function createDataProvider(): array
    {
        $test = TestModelFactory::create();

        return [
            'no tests, default maxResults, offset, limit' => [
                'tests' => [],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [],
            ],
            'no tests, non-default maxResults, offset, limit' => [
                'tests' => [],
                'maxResults' => 3,
                'offset' => 4,
                'limit' => 5,
                'expectedTests' => [],
            ],
            'no valid tests' => [
                'tests' => [
                    1,
                    true,
                    new \stdClass(),
                ],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [],
            ],
            'single valid test' => [
                'tests' => [
                    1,
                    true,
                    new \stdClass(),
                    $test,
                ],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [
                    $test,
                ],
            ],
        ];
    }

    /**
     * @dataProvider countableDataProvider
     */
    public function testCountable(array $tests, int $expectedCount)
    {
        $testList = new TestList($tests, 0, 0, 0);

        $this->assertEquals($expectedCount, count($testList));
    }

    public function countableDataProvider(): array
    {
        return [
            'one' => [
                'tests' => [
                    \Mockery::mock(Test::class),
                ],
                'expectedCount' => 1,
            ],
            'two' => [
                'tests' => [
                    \Mockery::mock(Test::class),
                    \Mockery::mock(Test::class),
                ],
                'expectedCount' => 2,
            ],
            'three' => [
                'tests' => [
                    \Mockery::mock(Test::class),
                    \Mockery::mock(Test::class),
                    \Mockery::mock(Test::class),
                ],
                'expectedCount' => 3,
            ],
        ];
    }

    public function testIterator()
    {
        $tests = [
            \Mockery::mock(Test::class),
            \Mockery::mock(Test::class),
            \Mockery::mock(Test::class),
        ];

        $testList = new TestList($tests, 3, 0, 3);

        foreach ($testList as $testIndex => $decoratedTest) {
            $this->assertSame($tests[$testIndex], $decoratedTest);
        }
    }

    /**
     * @dataProvider getPageNumberDataProvider
     */
    public function testGetPageNumber(int $offset, int $limit, int $expectedPageNumber)
    {
        $testList = new TestList([], 0, $offset, $limit);

        $this->assertEquals($expectedPageNumber, $testList->getPageNumber());
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
        $testList = new TestList([], $maxResults, 0, $limit);

        $this->assertEquals($expectedPageCount, $testList->getPageCount());
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
        $testList = new TestList([], 0, $offset, $limit);

        $this->assertEquals(
            $expectedPageCollectionIndex,
            ObjectReflector::getProperty($testList, 'pageCollectionIndex')
        );
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
}
