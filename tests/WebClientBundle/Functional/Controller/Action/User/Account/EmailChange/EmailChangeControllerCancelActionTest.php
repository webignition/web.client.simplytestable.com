<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\EmailChange;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EmailChangeControllerCancelActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_cancel';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/';

    /**
     * {@inheritdoc}
     */
    public function postRequestPublicUserDataProvider()
    {
        return [
            'default' => [
                'routeName' => self::ROUTE_NAME,
            ],
        ];
    }

    public function testCancelActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    public function testCancelAction()
    {
        $session = $this->container->get('session');

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->cancelAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                'user_account_details_cancel_email_change_notice' => [
                    'cancelled',
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
    }
}
