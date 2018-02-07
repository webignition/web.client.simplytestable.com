<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class EmailChangeControllerConfirmActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_confirm';
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

    public function testConfirmActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
            ]),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $user = new User('user@example.com', 'password');

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            'http://localhost/account/',
            $response->getTargetUrl()
        );
    }

    public function testConfirmActionEmptyToken()
    {
        $session = $this->container->get('session');

        $request = new Request([], []);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->confirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID,
            ],
        ], $session->getFlashBag()->peekAll());
    }

    public function testConfirmActionInvalidToken()
    {
        $session = $this->container->get('session');

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
            ]),
        ]);

        $request = new Request([], [
            'token' => 'foo',
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->confirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID,
            ],
        ], $session->getFlashBag()->peekAll());
    }

    /**
     * @dataProvider confirmActionChangeFailureDataProvider
     *
     * @param array $confirmEmailChangeRequestHttpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws \CredisException
     * @throws \Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException
     */
    public function testConfirmActionChangeFailure(
        array $confirmEmailChangeRequestHttpFixtures,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $coreApplicationHttpClient->setUser($userService->getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures(array_merge([
            HttpResponseFactory::createJsonResponse([
                'new_email' => 'new-email@example.com',
                'token' => 'token-value',
            ])
        ], $confirmEmailChangeRequestHttpFixtures));

        $request = new Request([], [
            'token' => 'token-value',
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->confirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function confirmActionChangeFailureDataProvider()
    {
        return [
            'email taken' => [
                'confirmEmailChangeRequestHttpFixtures' => [
                    HttpResponseFactory::createConflictResponse(),
                ],
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                        EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        'new-email@example.com',
                    ],
                ],
            ],
            'unknown error' => [
                'confirmEmailChangeRequestHttpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                        EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN,
                    ],
                ],
            ],
        ];
    }

    public function testConfirmActionSuccess()
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $resqueQueueService = $this->container->get('simplytestable.services.resque.queueservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $user = new User('user@example.com', 'password');
        $serializerUser = $userSerializerService->serializeToString($user);
        $newEmail = 'new-email@example.com';
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
                'new_email' => $newEmail,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $userService->setUser($user);

        $request = new Request([], [
            'token' => 'token-value',
        ], [], [
            'simplytestable-user' => $serializerUser,
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->confirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_MESSAGE_SUCCESS
            ]
        ], $session->getFlashBag()->peekAll());

        $updatedUser = $userService->getUser();
        $this->assertEquals($newEmail, $updatedUser->getUsername());

        $this->assertTrue($resqueQueueService->contains(
            'email-list-subscribe',
            [
                'listId' => 'announcements',
                'email' => $newEmail,
            ]
        ));

        $this->assertTrue($resqueQueueService->contains(
            'email-list-unsubscribe',
            [
                'listId' => 'announcements',
                'email' => $user->getUsername(),
            ]
        ));

        /* @var \Symfony\Component\HttpFoundation\Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();
        $rememberUserCookie = $responseCookies[0];

        $this->assertNotEquals($rememberUserCookie->getValue(), $serializerUser);
    }
}
