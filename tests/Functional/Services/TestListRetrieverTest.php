<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Model\Test as TestModel;
use App\Model\TestList;
use App\Services\TestListRetriever;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Services\ObjectReflector;

class TestListRetrieverTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var TestListRetriever
     */
    private $testListRetriever;

    protected function setUp()
    {
        parent::setUp();

        $this->testListRetriever = self::$container->get(TestListRetriever::class);
    }

    /**
     * @dataProvider getFinishedDataProvider
     */
    public function testGetFinished(
        array $httpFixtures,
        int $limit,
        int $offset,
        ?string $filter,
        int $expectedMaxResults,
        int $expectedLimit,
        int $expectedOffset,
        int $expectedTestCount,
        string $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $list = $this->testListRetriever->getFinished($limit, $offset, $filter);

        $this->assertInstanceOf(TestList::class, $list);

        $this->assertEquals($expectedMaxResults, $list->getMaxResults());
        $this->assertEquals($expectedLimit, ObjectReflector::getProperty($list, 'limit'));
        $this->assertEquals($expectedOffset, ObjectReflector::getProperty($list, 'offset'));
        $this->assertEquals($expectedTestCount, count($list));
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    public function getFinishedDataProvider(): array
    {
        return [
            'none; default' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [],
                    ]),
                ],
                'limit' => 10,
                'offset' => 0,
                'filter' => null,
                'expectedMaxResults' => 0,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 0,
                'expectedRequestUrl' => 'http://null/jobs/list/10/0/?exclude-states%5B0%5D=rejected&exclude-current=1',
            ],
            'none; with limit, offset and url-filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'limit' => 7,
                        'offset' => 3,
                        'jobs' => [],
                    ]),
                ],
                'limit' => 7,
                'offset' => 3,
                'filter' => 'foo',
                'expectedMaxResults' => 0,
                'expectedLimit' => 7,
                'expectedOffset' => 3,
                'expectedTestCount' => 0,
                'expectedRequestUrl' =>
                    'http://null/jobs/list/7/3/?exclude-states%5B0%5D=rejected&exclude-current=1&url-filter=foo',
            ],
            'one' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 10,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => 'user@example.com',
                                'website' => 'http://example.com/',
                                'state' => 'completed',
                                'url_count' => 12,
                                'task_types' => [],
                                'task_type_options' => [],
                                'type' => TestModel::TYPE_FULL_SITE,
                                'parameters' => '',
                            ],
                        ],
                    ]),
                ],
                'limit' => 10,
                'offset' => 0,
                'filter' => null,
                'expectedMaxResults' => 10,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 1,
                'expectedRequestUrl' => 'http://null/jobs/list/10/0/?exclude-states%5B0%5D=rejected&exclude-current=1',
            ],
        ];
    }

    /**
     * @dataProvider getRecentDataProvider
     */
    public function testGetRecent(
        array $httpFixtures,
        int $limit,
        int $expectedMaxResults,
        int $expectedLimit,
        int $expectedOffset,
        int $expectedTestCount,
        string $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $list = $this->testListRetriever->getRecent($limit);

        $this->assertInstanceOf(TestList::class, $list);

        $this->assertEquals($expectedMaxResults, $list->getMaxResults());
        $this->assertEquals($expectedLimit, ObjectReflector::getProperty($list, 'limit'));
        $this->assertEquals($expectedOffset, ObjectReflector::getProperty($list, 'offset'));
        $this->assertEquals($expectedTestCount, count($list));
        $this->assertEquals($expectedRequestUrl, (string) $this->httpHistory->getLastRequestUrl());
    }

    public function getRecentDataProvider(): array
    {
        return [
            'none; limit: 10' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [],
                    ]),
                ],
                'limit' => 10,
                'expectedMaxResults' => 0,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 0,
                'expectedRequestUrl' => 'http://null/jobs/list/10/0/?exclude-states%5B0%5D=new&'
                    . 'exclude-states%5B1%5D=preparing&exclude-states%5B2%5D=resolving'
                    . '&exclude-states%5B3%5D=resolved&exclude-states%5B4%5D=rejected&exclude-states%5B5%5D=queued',
            ],
            'none; limit: 3' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'limit' => 3,
                        'offset' => 0,
                        'jobs' => [],
                    ]),
                ],
                'limit' => 3,
                'expectedMaxResults' => 0,
                'expectedLimit' => 3,
                'expectedOffset' => 0,
                'expectedTestCount' => 0,
                'expectedRequestUrl' => 'http://null/jobs/list/3/0/?exclude-states%5B0%5D=new&'
                    . 'exclude-states%5B1%5D=preparing&exclude-states%5B2%5D=resolving'
                    . '&exclude-states%5B3%5D=resolved&exclude-states%5B4%5D=rejected&exclude-states%5B5%5D=queued',
            ],
            'one' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 10,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => 'user@example.com',
                                'website' => 'http://example.com/',
                                'state' => 'completed',
                                'url_count' => 12,
                                'task_types' => [],
                                'task_type_options' => [],
                                'type' => TestModel::TYPE_FULL_SITE,
                                'parameters' => '',
                            ],
                        ],
                    ]),
                ],
                'limit' => 3,
                'expectedMaxResults' => 10,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 1,
                'expectedRequestUrl' => 'http://null/jobs/list/3/0/?exclude-states%5B0%5D=new'
                    .'&exclude-states%5B1%5D=preparing&exclude-states%5B2%5D=resolving'
                    .'&exclude-states%5B3%5D=resolved&exclude-states%5B4%5D=rejected&exclude-states%5B5%5D=queued',
            ],
        ];
    }
}
