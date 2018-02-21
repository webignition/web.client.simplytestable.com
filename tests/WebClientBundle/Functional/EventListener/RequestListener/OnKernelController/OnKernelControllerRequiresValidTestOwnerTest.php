<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelController;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification\UrlLimitController;

class OnKernelControllerRequiresValidTestOwnerTest extends AbstractOnKernelControllerTest
{
    const WEBSITE = 'http://example.com';
    const TEST_ID = 1;

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasResponse
     *
     * @throws \ReflectionException
     * @throws CoreApplicationRequestException
     */
    public function testOnKernelController(array $httpFixtures, array $testValues, $expectedHasResponse)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $controller = new UrlLimitController();

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, UrlLimitController::class);

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
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ], 'foo'),
                ],
                'testValues' => [],
                'expectedHasResponse' => true,
            ],
            'invalid test owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
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
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }

    public function testOnKernelControllerCoreApplicationRequestException()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $testFactory = new TestFactory($this->container);
        $testFactory->create([
            TestFactory::KEY_WEBSITE => self::WEBSITE,
            TestFactory::KEY_TEST_ID => self::TEST_ID,
        ]);

        $controller = new UrlLimitController();

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
