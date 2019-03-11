<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Entity\Test;
use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
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
        $website = 'http://example.com/';

        $test = Test::create($testId, $website);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($test, $remoteTest);

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
            new DecoratedTest(
                Test::create(1, 'http://example.com/1/'),
                new RemoteTest([])
            ),
            new DecoratedTest(
                Test::create(2, 'http://example.com/2/'),
                new RemoteTest([])
            ),
            new DecoratedTest(
                Test::create(3, 'http://example.com/3/'),
                new RemoteTest([])
            )
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
                    new DecoratedTest(
                        Test::create(1, 'http://example.com/1/'),
                        new RemoteTest([])
                    )
                ],
                'expectedLength' => 1,
            ],
            'three tests' => [
                'decoratedTests' => [
                    new DecoratedTest(
                        Test::create(1, 'http://example.com/1/'),
                        new RemoteTest([])
                    ),
                    new DecoratedTest(
                        Test::create(2, 'http://example.com/2/'),
                        new RemoteTest([])
                    ),
                    new DecoratedTest(
                        Test::create(3, 'http://example.com/3/'),
                        new RemoteTest([])
                    )
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

    /**
     * @dataProvider getHashDataProvider
     */
    public function testGetHash(DecoratedTestList $decoratedTestList, string $expectedHash)
    {
        $this->assertEquals($expectedHash, $decoratedTestList->getHash());
    }

    public function getHashDataProvider(): array
    {
        return [
            'empty collection' => [
                'decoratedTestList' => new DecoratedTestList([], 0, 0, 0),
                'expectedHash' => '5087ab5bb6fff5750c18c697294fb7f1',
            ],
            'maxResults changes hash' => [
                'decoratedTestList' => new DecoratedTestList([], 1, 0, 0),
                'expectedHash' => '377f59d6e8ede3b212a7f85825b1685c',
            ],
            'foo' => [
                'decoratedTestList' => new DecoratedTestList([], 0, 0, 0),
                'expectedHash' => '5087ab5bb6fff5750c18c697294fb7f1',
            ],
            'offset changes hash' => [
                'decoratedTestList' => new DecoratedTestList([], 0, 1, 0),
                'expectedHash' => '9e218821ffa3de6f06f232a39ba33c9e',
            ],
            'limit changes hash' => [
                'decoratedTestList' => new DecoratedTestList([], 0, 0, 1),
                'expectedHash' => 'fbb50652521d14044d8d82b06939f412',
            ],
            'single test, not requiring remote tasks' => [
                'decoratedTestList' => new DecoratedTestList(
                    [
                        new DecoratedTest(
                            Test::create(1, 'http://example.com/'),
                            new RemoteTest([])
                        ),
                    ],
                    0,
                    0,
                    0
                ),
                'expectedHash' => '3226578dd6020326a0b45d92ff0a44e8',
            ],
            'single test, requiring remote tasks' => [
                'decoratedTestList' => new DecoratedTestList(
                    [
                        new DecoratedTest(
                            Test::create(1, 'http://example.com/'),
                            new RemoteTest([
                                'task_count' => 1,
                            ])
                        ),
                    ],
                    0,
                    0,
                    0
                ),
                'expectedHash' => 'f948e5c159192a2b3816f6f48cbf44e6',
            ],
        ];
    }
}
