<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelRequest;

use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelRequestRequiresValidUserTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial\RecentTestsController::indexAction';
    const CONTROLLER_ROUTE = 'view_dashboard_partial_recenttests_index';

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param $expectedIsRedirectResponse
     * @param string null $expectedRedirectUrl
     *
     * @throws \Exception
     */
    public function testOnKernelRequest(array $httpFixtures, $expectedIsRedirectResponse, $expectedRedirectUrl = null)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $request = new Request();

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE
        );

        $this->requestListener->onKernelRequest($event);

        $response = $event->getResponse();

        if ($expectedIsRedirectResponse) {
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
            'not authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedIsRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedIsRedirectResponse' => false,
            ],
        ];
    }
}
