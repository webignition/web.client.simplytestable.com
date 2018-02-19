<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelRequest;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelRequestRequiresCompletedTestTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\View\Test\Results\IndexController::indexAction';
    const CONTROLLER_ROUTE = 'view_test_results_index_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasRedirectResponse
     * @param string $expectedRedirectUrl
     *
     * @throws \Exception
     */
    public function testOnKernelRequest(
        array $httpFixtures,
        array $testValues,
        $expectedHasRedirectResponse,
        $expectedRedirectUrl = null
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE
        );

        $this->requestListener->onKernelRequest($event);

        $response = $event->getResponse();

        if ($expectedHasRedirectResponse) {
            /* @var RedirectResponse $response */
            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        } else {
            $this->assertNull($response);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'state: failed no sitemap' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_STATE => Test::STATE_FAILED_NO_SITEMAP,
                ],
                'expectedHasRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'state: rejected' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_REJECTED,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_STATE => Test::STATE_REJECTED,
                ],
                'expectedHasRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/rejected/',
            ],
            'state: in progress' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_IN_PROGRESS,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_STATE => Test::STATE_IN_PROGRESS,
                ],
                'expectedHasRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
            ],
            'non-matching website' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => 'http://foo.example.com/',
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [],
                'expectedHasRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
            ],
            'state: completed' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_STATE => Test::STATE_COMPLETED,
                ],
                'expectedHasRedirectResponse' => false,
            ],
        ];
    }
}
