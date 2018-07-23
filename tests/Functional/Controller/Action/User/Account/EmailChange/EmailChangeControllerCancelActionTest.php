<?php

namespace App\Tests\Functional\Controller\Action\User\Account\EmailChange;

use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use webignition\SimplyTestableUserModel\User;

class EmailChangeControllerCancelActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_cancel';
    const EXPECTED_REDIRECT_URL = '/account/';

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
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser(new User('user@example.com'));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME)
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
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures([
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
