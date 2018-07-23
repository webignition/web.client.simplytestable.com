<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\Controller\View\Partials\RecentTestsController;
use App\EventListener\RequiresValidUserRequestListener;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelControllerRequiresValidUserTest extends AbstractKernelControllerTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresValidUserRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param $expectedHasResponse
     * @param string null $expectedRedirectUrl
     *
     * @throws \Exception
     */
    public function testOnKernelController(array $httpFixtures, $expectedHasResponse, $expectedRedirectUrl = null)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request();

        /* @var RecentTestsController $controller */
        $controller = self::$container->get(RecentTestsController::class);

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, RecentTestsController::class);

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
            'not authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/signout/',
            ],
            'authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }
}
