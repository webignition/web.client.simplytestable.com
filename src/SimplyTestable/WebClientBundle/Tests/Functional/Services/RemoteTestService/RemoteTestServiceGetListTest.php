<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Model\TestList;

class RemoteTestServiceGetListTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var HistoryPlugin
     */
    private $httpHistoryPlugin;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->getHttpClientService();
        $httpClientService->get()->addSubscriber($this->httpHistoryPlugin);
    }

    public function testGetCurrentWebResourceException()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 500'),
        ]);
        $this->remoteTestService->setUser($this->user);

        $currentList = $this->remoteTestService->getCurrent();

        $this->assertInstanceOf(TestList::class, $currentList);

        $this->assertEquals(0, $currentList->getLength());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/jobs/list/100/0/?exclude-finished=1',
            $lastRequest->getUrl()
        );
    }

    /**
     * @dataProvider getCurrentDataProvider
     *
     * @param array $httpFixtures
     * @param int $expectedMaxResults
     * @param int $expectedLimit
     * @param int $expectedOffset
     * @param int $expectedTestCount
     */
    public function testGetCurrent(
        array $httpFixtures,
        $expectedMaxResults,
        $expectedLimit,
        $expectedOffset,
        $expectedTestCount
    ) {
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

        $currentList = $this->remoteTestService->getCurrent();

        $this->assertInstanceOf(TestList::class, $currentList);

        $this->assertEquals($expectedMaxResults, $currentList->getMaxResults());
        $this->assertEquals($expectedLimit, $currentList->getLimit());
        $this->assertEquals($expectedOffset, $currentList->getOffset());
        $this->assertEquals($expectedTestCount, $currentList->getLength());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/jobs/list/100/0/?exclude-finished=1',
            $lastRequest->getUrl()
        );
    }

    /**
     * @return array
     */
    public function getCurrentDataProvider()
    {
        return [
            'none' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'max_results' => 0,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [],
                    ])),
                ],
                'expectedMaxResults' => 0,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 0,
            ],
            'one' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
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
                    ])),
                ],
                'expectedMaxResults' => 10,
                'expectedLimit' => 100,
                'expectedOffset' => 0,
                'expectedTestCount' => 1,
            ],
        ];
    }

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
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

        $finishedList = $this->remoteTestService->getFinished($limit, $offset, $filter);

        $this->assertInstanceOf(TestList::class, $finishedList);

        $this->assertEquals($expectedMaxResults, $finishedList->getMaxResults());
        $this->assertEquals($expectedLimit, $finishedList->getLimit());
        $this->assertEquals($expectedOffset, $finishedList->getOffset());
        $this->assertEquals($expectedTestCount, $finishedList->getLength());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();
        $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
    }

    /**
     * @return array
     */
    public function getFinishedDataProvider()
    {
        return [
            'none; default' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                            'max_results' => 0,
                            'limit' => 100,
                            'offset' => 0,
                            'jobs' => [],
                        ])),
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
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'max_results' => 0,
                        'limit' => 7,
                        'offset' => 3,
                        'jobs' => [],
                    ])),
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
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
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
                    ])),
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
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

        $finishedList = $this->remoteTestService->getRecent($limit);

        $this->assertInstanceOf(TestList::class, $finishedList);

        $this->assertEquals($expectedMaxResults, $finishedList->getMaxResults());
        $this->assertEquals($expectedLimit, $finishedList->getLimit());
        $this->assertEquals($expectedOffset, $finishedList->getOffset());
        $this->assertEquals($expectedTestCount, $finishedList->getLength());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();
        $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
    }

    /**
     * @return array
     */
    public function getRecentDataProvider()
    {
        return [
            'none; limit: 10' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'max_results' => 0,
                        'limit' => 100,
                        'offset' => 0,
                        'jobs' => [],
                    ])),
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
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'max_results' => 0,
                        'limit' => 3,
                        'offset' => 0,
                        'jobs' => [],
                    ])),
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
