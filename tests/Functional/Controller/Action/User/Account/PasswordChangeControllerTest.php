<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\User\Account;

use App\Controller\Action\User\Account\PasswordChangeController;
use App\Services\UserManager;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class PasswordChangeControllerTest extends AbstractUserAccountControllerTest
{
    const USER_EMAIL = 'user@example.com';
    const USER_CURRENT_PASSWORD = 'Current-Password';

    const ROUTE_NAME = 'action_user_account_passwordchange_request';

    /**
     * @var PasswordChangeController
     */
    private $passwordChangeController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->passwordChangeController = self::$container->get(PasswordChangeController::class);
    }

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

    public function testRequestActionInvalidUserPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testRequestActionPostRequest()
    {
        $userSerializer = self::$container->get(UserSerializer::class);
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userManager->setUser($user);

        $this->client->getCookieJar()->set(
            new Cookie(UserManager::USER_COOKIE_KEY, $userSerializer->serializeToString($user))
        );

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse('token-value'),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = self::$container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'current-password' => self::USER_CURRENT_PASSWORD,
                'new-password' => 'foo',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/account/'));
    }

    /**
     * @dataProvider requestActionDataProvider
     */
    public function testRequestActionSuccess(
        array $httpFixtures,
        Request $request,
        array $expectedFlashBagValues,
        string $expectedUserPassword
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->passwordChangeController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());

        $this->assertEquals(
            $expectedFlashBagValues,
            $flashBag->get(PasswordChangeController::FLASH_BAG_REQUEST_KEY)
        );

        $this->assertEquals($expectedUserPassword, $user->getPassword());
    }

    public function requestActionDataProvider(): array
    {
        return [
            'current password missing' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'new password missing' => [
                'httpFixtures' => [],
                'request' => new Request([], [
                    'current-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'current password invalid' => [
                'httpFixtures' => [],
                'request' => new Request([], [
                    'current-password' => 'foo',
                    'new-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'failed; core application read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse('token-value'),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'failed; core application HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse('token-value'),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'failed; CURL 28' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse('token-value'),
                    ConnectExceptionFactory::create(28, 'Operation timed out'),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN,
                ],
                'expectedUserPassword' => self::USER_CURRENT_PASSWORD,
            ],
            'success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse('token-value'),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_SUCCESS_MESSAGE,
                ],
                'expectedUserPassword' => 'foo',
            ],
            'success; with auth cookie' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse('token-value'),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ], [], [
                    UserManager::USER_COOKIE_KEY => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_SUCCESS_MESSAGE,
                ],
                'expectedUserPassword' => 'foo',
            ],
        ];
    }
}
