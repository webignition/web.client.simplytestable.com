<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\User;

use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use webignition\SimplyTestableUserModel\User;

class SignOutSubmitActionTest extends AbstractUserControllerTest
{
    const USER_EMAIL = 'user@example.com';

    public function testSignOutSubmitActionPostRequest()
    {
        $this->client->request(
            'POST',
            $this->router->generate('sign_out_submit')
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function testSignOutSubmitAction()
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        /* @var RedirectResponse $response */
        $response = $this->userController->signOutSubmitAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());

        /* @var Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();
        $this->assertCount(1, $responseCookies);

        $responseCookie = $responseCookies[0];

        $this->assertEquals(UserManager::USER_COOKIE_KEY, $responseCookie->getName());
        $this->assertNull($responseCookie->getValue());
        $this->assertEquals(1, $responseCookie->getExpiresTime());
    }
}
