<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelController;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\NewsSubscriptionsController;

class OnKernelControllerRequiresPrivateUserTest extends AbstractOnKernelControllerTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param $expectedHasResponse
     * @param string $expectedRedirectUrl
     *
     * @throws CoreApplicationRequestException
     */
    public function testOnKernelController(
        array $httpFixtures,
        User $user,
        $expectedHasResponse,
        $expectedRedirectUrl = null
    ) {
        $userManager = $this->container->get(UserManager::class);
        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $controller = new NewsSubscriptionsController();

        $request = new Request();

        $event = $this->createFilterControllerEvent($request, $controller, 'updateAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, NewsSubscriptionsController::class);

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
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => SystemUserService::getPublicUser(),
                'expectedHasResponse' => true,
                'expectedRedirectUrl' =>
                    'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D',
            ],
            'private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com'),
                'expectedHasResponse' => false,
            ],
        ];
    }
}
