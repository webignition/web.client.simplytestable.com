<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\EventListener\RequestListener;

use App\Entity\Test\Test;
use App\EventListener\RequiresCompletedTestRequestListener;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;
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
        $expectedHasResponse,
        $expectedRedirectUrl = null
    ) {
        $httpFixture = HttpResponseFactory::createJsonResponse([
            'id' => self::TEST_ID,
            'website' => self::WEBSITE,
            'task_types' => [],
            'user' => 'user@example.com',
            'state' => $testState,
        ]);

        $this->httpMockHandler->appendFixtures([$httpFixture]);

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
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'view_test_results_failed_no_urls_detected, state: failed no sitemap' => [
                'route' => 'view_test_results_failed_no_urls_detected',
                'testState' => Test::STATE_FAILED_NO_SITEMAP,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: rejected' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_REJECTED,
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/rejected/',
            ],
            'view_test_results_rejected, state: rejected' => [
                'route' => 'view_test_results_rejected',
                'testState' => Test::STATE_REJECTED,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: in progress' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_IN_PROGRESS,
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'view_test_progress, state: in progress' => [
                'route' => 'view_test_progress',
                'testState' => Test::STATE_IN_PROGRESS,
                'expectedHasResponse' => false,
            ],
            'view_test_results, state: completed' => [
                'route' => 'view_test_results',
                'testState' => Test::STATE_COMPLETED,
                'expectedHasResponse' => false,
            ],
        ];
    }
}
