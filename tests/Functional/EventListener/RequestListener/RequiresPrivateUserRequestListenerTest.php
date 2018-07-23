<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\RequiresPrivateUserRequestListener;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Action\User\Account\NewsSubscriptionsController;
use webignition\SimplyTestableUserModel\User;

class RequiresPrivateUserRequestListenerTest extends AbstractKernelControllerTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresPrivateUserRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param $expectedHasResponse
     * @param string $expectedRedirectUrl
     */
    public function testOnKernelController(
        array $httpFixtures,
        User $user,
        $expectedHasResponse,
        $expectedRedirectUrl = null
    ) {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var NewsSubscriptionsController $controller */
        $controller = self::$container->get(NewsSubscriptionsController::class);

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
                    '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D',
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
