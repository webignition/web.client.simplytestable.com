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
        $this->assertEquals($offset, $testList->getOffset());
        $this->assertEquals($limit, $testList->getLimit());
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
}
