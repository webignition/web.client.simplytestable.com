<?php

namespace Tests\AppBundle\Functional\Services\RemoteTestService;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\TestList;
use Tests\AppBundle\Factory\HttpResponseFactory;

class RemoteTestServiceGetListTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedDataProvider
     *
     * @param array $httpFixtures
     * @param int $limit
     * @param int $offset
     * @param string $filter
     * @param int $expectedMaxResults
     * @param int $expectedLimit
     * @param int $expectedOffset
     * @param int $expectedTestCount
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetFinished(
        array $httpFixtures,
        $limit,
        $offset,
        $filter,
        $expectedMaxResults,
        $expectedLimit,
        $expectedOffset,
        $expectedTestCount,
        $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $finishedList = $this->remoteTestService->getFinished($limit, $offset, $filter);

        $this->assertInstanceOf(TestList::class, $finishedList);

        $this->assertEquals($expectedMaxResults, $finishedList->getMaxResults());
        $this->assertEquals($expectedLimit, $finishedList->getLimit());
        $this->assertEquals($expectedOffset, $finishedList->getOffset());
        $this->assertEquals($expectedTestCount, $finishedList->getLength());
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function getFinishedDataProvider()
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
                                'type' => 'Full site',
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
     *
     * @param array $httpFixtures
     * @param int $limit
     * @param int $expectedMaxResults
     * @param int $expectedLimit
     * @param int $expectedOffset
     * @param int $expectedTestCount
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetRecent(
        array $httpFixtures,
        $limit,
        $expectedMaxResults,
        $expectedLimit,
        $expectedOffset,
        $expectedTestCount,
        $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $finishedList = $this->remoteTestService->getRecent($limit);

        $this->assertInstanceOf(TestList::class, $finishedList);

        $this->assertEquals($expectedMaxResults, $finishedList->getMaxResults());
        $this->assertEquals($expectedLimit, $finishedList->getLimit());
        $this->assertEquals($expectedOffset, $finishedList->getOffset());
        $this->assertEquals($expectedTestCount, $finishedList->getLength());
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function getRecentDataProvider()
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
        ];
    }
}
