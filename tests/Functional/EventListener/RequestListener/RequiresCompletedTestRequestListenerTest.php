<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\EventListener\RequestListener;

use App\Model\Test;
use App\EventListener\RequiresCompletedTestRequestListener;
use App\Services\TestRetriever;
use App\Tests\Factory\TestFactory;
use App\Tests\Services\ObjectReflector;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RequiresCompletedTestRequestListenerTest extends AbstractKernelRequestListenerTest
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresCompletedTestRequestListener::class);
    }

    /**
     * @dataProvider onKernelRequestDataProvider
     */
    public function testOnKernelRequest(
        string $route,
        string $testState,
        int $isFinished,
        bool $expectedHasResponse,
        ?string $expectedRedirectUrl = null
    ) {
        $test = \Mockery::mock(Test::class);
        $test
            ->shouldReceive('getState')
            ->andReturn($testState);

        $test
            ->shouldReceive('isFinished')
            ->andReturn($isFinished);

        $testRetriever = \Mockery::mock(TestRetriever::class);
        $testRetriever
            ->shouldReceive('retrieve')
            ->with(self::TEST_ID)
            ->andReturn($test);

        ObjectReflector::setProperty(
            $this->requestListener,
            RequiresCompletedTestRequestListener::class,
            'testRetriever',
            $testRetriever
        );

        $router = self::$container->get(RouterInterface::class);
        $testFactory = new TestFactory(self::$container);

        $routeParameters = [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ];

        $testValues = [
            TestFactory::KEY_WEBSITE => self::WEBSITE,
            TestFactory::KEY_TEST_ID => self::TEST_ID,
            TestFactory::KEY_STATE => $testState,
        ];

        $testFactory->create($testValues);

        $url = $router->generate($route, $routeParameters);

        $event = $this->createGetResponseEvent(new Request(
            [],
            [],
            array_merge(
                [
                    '_route' => $route,
                ],
                $routeParameters
            ),
            [],
            [],
            [
                'REQUEST_URI' => $url,
            ]
        ));

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());

        if ($expectedHasResponse) {
            /* @var RedirectResponse $response */
            $response = $event->getResponse();

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        }
    }

    public function onKernelRequestDataProvider(): array
    {
        return [
            'view_test_results, state: failed no sitemap' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_FAILED_NO_SITEMAP,
                'isFinished' => true,
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'view_test_results_failed_no_urls_detected, state: failed no sitemap' => [
                'route' => 'view_test_results_failed_no_urls_detected',
                'testState' => Test::STATE_FAILED_NO_SITEMAP,
                'isFinished' => true,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: rejected' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_REJECTED,
                'isFinished' => true,
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/rejected/',
            ],
            'view_test_results_rejected, state: rejected' => [
                'route' => 'view_test_results_rejected',
                'testState' => Test::STATE_REJECTED,
                'isFinished' => true,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: in progress' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_IN_PROGRESS,
                'isFinished' => false,
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'view_test_progress, state: in progress' => [
                'route' => 'view_test_progress',
                'testState' => Test::STATE_IN_PROGRESS,
                'isFinished' => false,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: completed' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_COMPLETED,
                'isFinished' => true,
                'expectedHasResponse' => false,
            ],
        ];
    }
}
