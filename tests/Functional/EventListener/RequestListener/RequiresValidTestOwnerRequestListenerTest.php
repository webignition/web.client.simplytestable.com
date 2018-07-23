<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\Entity\Test\Test;
use App\EventListener\RequiresValidTestOwnerRequestListener;
use App\Exception\CoreApplicationRequestException;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\View\Partials\TestUrlLimitNotificationController;

class RequiresValidTestOwnerRequestListenerTest extends AbstractKernelControllerTest
{
    const WEBSITE = 'http://example.com';
    const TEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresValidTestOwnerRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasResponse
     *
     * @throws \ReflectionException
     */
    public function testOnKernelController(array $httpFixtures, array $testValues, $expectedHasResponse)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        /* @var TestUrlLimitNotificationController $controller */
        $controller = self::$container->get(TestUrlLimitNotificationController::class);

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, TestUrlLimitNotificationController::class);

            $this->assertInstanceOf(Response::class, $response);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'invalid test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ], 'foo'),
                ],
                'testValues' => [],
                'expectedHasResponse' => true,
            ],
            'invalid test owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                ],
                'expectedHasResponse' => true,
            ],
            'valid test owner' => [
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
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }

    public function testOnKernelControllerCoreApplicationRequestException()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $testFactory = new TestFactory(self::$container);
        $testFactory->create([
            TestFactory::KEY_WEBSITE => self::WEBSITE,
            TestFactory::KEY_TEST_ID => self::TEST_ID,
        ]);

        /* @var TestUrlLimitNotificationController $controller */
        $controller = self::$container->get(TestUrlLimitNotificationController::class);

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->expectException(CoreApplicationRequestException::class);
        $this->expectExceptionMessage('Not Found');
        $this->expectExceptionCode(404);

        $this->requestListener->onKernelController($event);
    }
}
