<?php

namespace Tests\AppBundle\Functional\EventListener\RequestListener;

use App\Controller\BaseViewController;
use App\Entity\Test\Test;
use App\EventListener\RequiresCompletedTestRequestListener;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Factory\TestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\View\Test\Results\IndexController;

class RequiresCompletedTestRequestListenerTest extends AbstractKernelControllerTest
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
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasResponse
     * @param string $expectedRedirectUrl
     *
     * @throws \ReflectionException
     */
    public function testOnKernelController(
        array $httpFixtures,
        array $testValues,
        $expectedHasResponse,
        $expectedRedirectUrl = null
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        /* @var IndexController $controller */
        $controller = self::$container->get(IndexController::class);

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, BaseViewController::class);

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
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
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'state: rejected' => [
                'httpFixtures' => [
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
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/rejected/',
            ],
            'state: in progress' => [
                'httpFixtures' => [
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
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'non-matching website' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => 'http://foo.example.com/',
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [],
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/http://example.com//1/',
            ],
            'state: completed' => [
                'httpFixtures' => [
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
                'expectedHasResponse' => false,
            ],
        ];
    }
}
