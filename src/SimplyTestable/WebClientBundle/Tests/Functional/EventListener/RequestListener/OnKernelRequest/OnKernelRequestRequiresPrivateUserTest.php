<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelRequestRequiresPrivateUserTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\Action\User\Account\NewsSubscriptionsController::updateAction';
    const CONTROLLER_ROUTE = 'action_user_account_newssubscriptions_update';

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param $expectedIsRedirectResponse
     * @param string $expectedRedirectUrl
     *
     * @throws \Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testOnKernelRequest(
        array $httpFixtures,
        User $user,
        $expectedIsRedirectResponse,
        $expectedRedirectUrl = null
    ) {
        $this->setHttpFixtures($httpFixtures);

        $request = new Request();

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE,
            $user
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
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'expectedIsRedirectResponse' => true,
                'expectedRedirectUrl' =>
                    'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D',
            ],
            'private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com'),
                'expectedIsRedirectResponse' => false,
            ],
        ];
    }
}
