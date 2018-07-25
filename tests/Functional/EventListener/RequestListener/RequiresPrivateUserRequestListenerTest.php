<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\RequiresPrivateUserRequestListener;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use webignition\SimplyTestableUserModel\User;

class RequiresPrivateUserRequestListenerTest extends AbstractKernelRequestListenerTest
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
    public function testOnKernelRequest(
        array $httpFixtures,
        User $user,
        $expectedHasResponse,
        $expectedRedirectUrl = null
    ) {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $event = $this->createGetResponseEvent(new Request([], [], [], [], [], [
            'REQUEST_URI' => '/account/',
        ]));

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());

        if ($expectedHasResponse) {
            /* @var RedirectResponse $response */
            $response = $event->getResponse();

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        } else {
            $this->assertTrue(true);
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
                'expectedRedirectUrl' => '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50In0%3D',
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
