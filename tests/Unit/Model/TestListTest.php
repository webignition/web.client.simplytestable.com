<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Model\RemoteTest\RemoteTest;
use App\Model\TestList;

class TestListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(array $remoteTests, int $maxResults, int $offset, int $limit, array $expectedTests)
    {
        $testList = new TestList($remoteTests, $maxResults, $offset, $limit);

        $this->assertEquals($expectedTests, $testList->get());
        $this->assertEquals($maxResults, $testList->getMaxResults());
        $this->assertEquals($offset, $testList->getOffset());
        $this->assertEquals($limit, $testList->getLimit());
    }

    public function createDataProvider(): array
    {
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
            'no valid remote tests' => [
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
            'single valid remote tests' => [
                'remoteTests' => [
                    1,
                    true,
                    new \stdClass(),
                    new RemoteTest([
                        'id' => 7,
                    ]),
                ],
                'maxResults' => 0,
                'offset' => 0,
                'limit' => 1,
                'expectedTests' => [
                    7 => [
                        'remote_test' => new RemoteTest([
                            'id' => 7,
                        ]),
                    ],
                ],
            ],
        ];
    }
}
