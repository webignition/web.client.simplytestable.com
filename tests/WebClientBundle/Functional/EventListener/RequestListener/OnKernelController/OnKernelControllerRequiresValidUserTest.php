<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelController;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial\RecentTestsController;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelControllerRequiresValidUserTest extends AbstractOnKernelControllerTest
{
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
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $request = new Request();

        $controller = new RecentTestsController();

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
                'expectedRedirectUrl' => 'http://localhost/signout/',
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
