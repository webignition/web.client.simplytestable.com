<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\PasswordChangeController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PasswordChangeControllerTest extends AbstractBaseTestCase
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

        $this->passwordChangeController = new PasswordChangeController();
        $this->passwordChangeController->setContainer($this->container);
    }

    public function testRequestActionInvalidUserPostRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testRequestActionNotPrivateUserPostRequest()
    {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect(
            'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D'
        ));
    }

    public function testRequestActionPostRequest()
    {
        $userSerializerService = $this->container->get(UserSerializerService::class);
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userManager->setUser($user);

        $this->client->getCookieJar()->set(
            new Cookie(UserManager::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse('token-value'),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
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

        $this->assertTrue($response->isRedirect('http://localhost/account/'));
    }

    /**
     * @dataProvider requestActionDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param array $expectedFlashBagValues
     * @param string $expectedUserPassword
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testRequestActionSuccess(
        array $httpFixtures,
        Request $request,
        array $expectedFlashBagValues,
        $expectedUserPassword
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->passwordChangeController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());

        $this->assertEquals(
            $expectedFlashBagValues,
            $session->getFlashBag()->get(PasswordChangeController::FLASH_BAG_REQUEST_KEY)
        );

        $this->assertEquals($expectedUserPassword, $user->getPassword());
    }

    /**
     * @return array
     */
    public function requestActionDataProvider()
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
                    ConnectExceptionFactory::create('CURL/28 Operation timed out'),
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
