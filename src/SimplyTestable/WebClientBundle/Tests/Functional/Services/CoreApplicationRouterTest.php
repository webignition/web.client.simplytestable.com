<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Services\CoreApplicationRouter;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

class CoreApplicationRouterTest extends AbstractBaseTestCase
{
    /**
     * @var CoreApplicationRouter
     */
    private $coreApplicationRouter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->coreApplicationRouter = $this->container->get(CoreApplicationRouter::class);
    }

    /**
     * @dataProvider generateDataProvider
     *
     * @param string $route
     * @param array $parameters
     * @param string $expectedUrl
     */
    public function testGenerate($route, array $parameters, $expectedUrl)
    {
        $url = $this->coreApplicationRouter->generate($route, $parameters);

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @return array
     */
    public function generateDataProvider()
    {
        return [
            'test_start, no test type, no test options' => [
                'route' => 'test_start',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/',
            ],
            'test_start, has test type, no test options' => [
                'route' => 'test_start',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'type' => strtolower(Test::TYPE_FULL_SITE),
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/?' . http_build_query([
                    'type' => strtolower(Test::TYPE_FULL_SITE),
                ]),
            ],
            'test_start, has test type, has test options' => [
                'route' => 'test_start',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'type' => strtolower(Test::TYPE_FULL_SITE),
                    'test_types' => [
                        Task::TYPE_HTML_VALIDATION,
                        Task::TYPE_CSS_VALIDATION,
                    ],
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/?' . http_build_query([
                    'type' => strtolower(Test::TYPE_FULL_SITE),
                    'test_types' => [
                        Task::TYPE_HTML_VALIDATION,
                        Task::TYPE_CSS_VALIDATION,
                    ],
                ]),
            ],
            'test_status' => [
                'route' => 'test_status',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'test_id' => 1,
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/1/',
            ],
            'test_cancel' => [
                'route' => 'test_cancel',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'test_id' => 1,
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/1/cancel/',
            ],
            'test_retest' => [
                'route' => 'test_retest',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'test_id' => 1,
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/1/re-test/',
            ],
            'test_set_public' => [
                'route' => 'test_set_public',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'test_id' => 1,
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/1/set-public/',
            ],
            'test_set_private' => [
                'route' => 'test_set_private',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                    'test_id' => 1,
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/1/set-private/',
            ],
            'tests_list' => [
                'route' => 'tests_list',
                'parameters' => [
                    'limit' => 1,
                    'offset' => 2,
                ],
                'expectedUrl' => 'http://null/jobs/list/1/2/',
            ],
            'tests_list_websites' => [
                'route' => 'tests_list_websites',
                'parameters' => [],
                'expectedUrl' => 'http://null/jobs/list/websites/',
            ],
            'tests_list_count' => [
                'route' => 'tests_list_count',
                'parameters' => [],
                'expectedUrl' => 'http://null/jobs/list/count/',
            ],
            'test_latest' => [
                'route' => 'test_latest',
                'parameters' => [
                    'canonical_url' => 'http://example.com',
                ],
                'expectedUrl' => 'http://null/job/http%3A%2F%2Fexample.com/latest/',
            ],
        ];
    }
}
