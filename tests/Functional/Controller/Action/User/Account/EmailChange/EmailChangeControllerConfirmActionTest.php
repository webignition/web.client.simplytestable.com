<?php

namespace App\Tests\Functional\Controller\Action\User\Account\EmailChange;

use App\Controller\Action\User\Account\EmailChangeController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\ResqueQueueService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class EmailChangeControllerConfirmActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_confirm';
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

    public function testConfirmActionPostRequestPrivateUser()
    {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser(new User('user@example.com'));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            '/account/',
            $response->getTargetUrl()
        );
    }

    public function testConfirmActionEmptyToken()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $request = new Request([], []);

        $response = $this->callConfirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID,
            ],
        ], $flashBag->peekAll());
    }

    public function testConfirmActionInvalidToken()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
            ]),
        ]);

        $request = new Request([], [
            'token' => 'foo',
        ]);

        $response = $this->callConfirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID,
            ],
        ], $flashBag->peekAll());
    }

    public function testConfirmActionNoEmailChangeRequest()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $request = new Request([], [
            'token' => 'foo',
        ]);

        $response = $this->callConfirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
        $this->assertEquals([], $flashBag->peekAll());
    }

    /**
     * @dataProvider confirmActionChangeFailureDataProvider
     *
     * @param array $confirmEmailChangeRequestHttpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws \CredisException
     * @throws \Exception
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    public function testConfirmActionChangeFailure(
        array $confirmEmailChangeRequestHttpFixtures,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures(array_merge([
            HttpResponseFactory::createJsonResponse([
                'new_email' => 'new-email@example.com',
                'token' => 'token-value',
            ])
        ], $confirmEmailChangeRequestHttpFixtures));

        $request = new Request([], [
            'token' => 'token-value',
        ]);

        $response = $this->callConfirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
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
        $flashBag = self::$container->get(FlashBagInterface::class);
        $resqueQueueService = self::$container->get(ResqueQueueService::class);
        $userSerializer = self::$container->get(UserSerializer::class);
        $userManager = self::$container->get(UserManager::class);

        $userEmail = 'user@example.com';
        $userNewEmail = 'new-email@example.com';

        $user = new User($userEmail, 'password');
        $serializerUser = $userSerializer->serializeToString($user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'token-value',
                'new_email' => $userNewEmail,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $userManager->setUser($user);

        $request = new Request([], [
            'token' => 'token-value',
        ], [], [
            'simplytestable-user' => $serializerUser,
        ]);

        $response = $this->callConfirmAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_CONFIRM_KEY => [
                EmailChangeController::FLASH_BAG_CONFIRM_MESSAGE_SUCCESS
            ]
        ], $flashBag->peekAll());

        $updatedUser = $userManager->getUser();
        $this->assertEquals($userNewEmail, $updatedUser->getUsername());

        $this->assertTrue($resqueQueueService->contains(
            'email-list-subscribe',
            [
                'listId' => 'announcements',
                'email' => $userNewEmail,
            ]
        ));

        $this->assertTrue($resqueQueueService->contains(
            'email-list-unsubscribe',
            [
                'listId' => 'announcements',
                'email' => $userEmail,
            ]
        ));

        /* @var \Symfony\Component\HttpFoundation\Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();
        $rememberUserCookie = $responseCookies[0];

        $this->assertNotEquals($rememberUserCookie->getValue(), $serializerUser);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws \CredisException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function callConfirmAction(Request $request)
    {
        return $this->emailChangeController->confirmAction(
            self::$container->get(ResqueQueueService::class),
            $request
        );
    }
}
