<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SignOutSubmitActionTest extends AbstractUserControllerTest
{
    const USER_EMAIL = 'user@example.com';

    public function testSignOutSubmitActionPostRequest()
    {
        $router = $this->container->get('router');
        $requestUrl = $router->generate('sign_out_submit');

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals('http://localhost/', $response->getTargetUrl());
    }

    public function testSignOutSubmitAction()
    {
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = new User(self::USER_EMAIL);
        $userService->setUser($user);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signOutSubmitAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/', $response->getTargetUrl());

        /* @var Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();
        $this->assertCount(1, $responseCookies);

        $responseCookie = $responseCookies[0];

        $this->assertEquals(UserService::USER_COOKIE_KEY, $responseCookie->getName());
        $this->assertNull($responseCookie->getValue());
        $this->assertEquals(1, $responseCookie->getExpiresTime());
    }
}