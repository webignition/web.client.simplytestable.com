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
        ];
    }
}
