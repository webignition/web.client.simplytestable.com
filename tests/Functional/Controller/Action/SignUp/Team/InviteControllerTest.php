<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\SignUp\Team;

use App\Controller\Action\SignUp\Team\InviteController;
use App\Services\ResqueQueueService;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class InviteControllerTest extends AbstractControllerTest
{
    const USERNAME = 'user@example.com';
    const TOKEN = 'tokenValue';
    const PASSWORD = 'passwordValue';

    /**
     * @var InviteController
     */
    private $inviteController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->inviteController = self::$container->get(InviteController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testAcceptActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => 'Team Name',
                'user' => self::USERNAME,
                'token' => self::TOKEN,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('action_sign_up_team_invite_accept', [
                'token' => self::TOKEN,
            ]),
            [
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/'));
    }

    /**
     * @dataProvider acceptActionFailureDataProvider
     */
    public function testAcceptActionFailure(
        array $httpFixtures,
        string $token,
        Request $request,
        string $expectedRedirectUrl,
        array $expectedErrorFlashBagValues,
        array $expectedFailureFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->inviteController->acceptAction($request, $token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $this->assertEquals(
            $expectedErrorFlashBagValues,
            $flashBag->get(InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY)
        );

        $this->assertEquals(
            $expectedFailureFlashBagValues,
            $flashBag->get(InviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY)
        );
    }

    public function acceptActionFailureDataProvider(): array
    {
        $notFoundResponse = HttpResponseFactory::createNotFoundResponse();
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        $inviteHttpResponse = HttpResponseFactory::createJsonResponse([
            'team' => 'Team Name',
            'user' => self::USERNAME,
            'token' => self::TOKEN,
        ]);

        return [
            'empty token' => [
                'httpFixtures' => [],
                'token' => '',
                'request' => new Request(),
                'expectedRedirectUrl' => '/signup/',
                'expectedErrorFlashBagValues' => [],
                'expectedFailureFlashBagValues' => [],
            ],
            'invalid token' => [
                'httpFixtures' => [
                    $notFoundResponse,
                ],
                'token' => 'invalid token',
                'request' => new Request(),
                'expectedRedirectUrl' => '/signup/',
                'expectedErrorFlashBagValues' => [],
                'expectedFailureFlashBagValues' => [],
            ],
            'missing password' => [
                'httpFixtures' => [
                    $inviteHttpResponse,
                ],
                'token' => self::TOKEN,
                'request' => new Request(),
                'expectedRedirectUrl' => '/signup/invite/tokenValue/',
                'expectedErrorFlashBagValues' => [
                    InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK,
                ],
                'expectedFailureFlashBagValues' => [],
            ],
            'activateAndAccept failure; HTTP 500' => [
                'httpFixtures' => [
                    $inviteHttpResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'token' => self::TOKEN,
                'request' => new Request([], [
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectUrl' => '/signup/invite/tokenValue/',
                'expectedErrorFlashBagValues' => [
                    InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                ],
                'expectedFailureFlashBagValues' => [
                    500,
                ],
            ],
            'activateAndAccept failure; CURL 28' => [
                'httpFixtures' => [
                    $inviteHttpResponse,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'token' => self::TOKEN,
                'request' => new Request([], [
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectUrl' => '/signup/invite/tokenValue/',
                'expectedErrorFlashBagValues' => [
                    InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                ],
                'expectedFailureFlashBagValues' => [
                    28,
                ],
            ],
        ];
    }

    /**
     * @dataProvider acceptActionSuccessDataProvider
     */
    public function testAcceptActionSuccess(Request $request, bool $expectedHasUserCookie)
    {
        $resqueQueueService = self::$container->get(ResqueQueueService::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => 'Team Name',
                'user' => self::USERNAME,
                'token' => self::TOKEN,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->inviteController->acceptAction($request, self::TOKEN);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());

        $this->assertTrue($resqueQueueService->contains(
            'email-list-subscribe',
            [
                'listId' => 'announcements',
                'email' => self::USERNAME,
            ]
        ));

        $this->assertTrue($resqueQueueService->contains(
            'email-list-subscribe',
            [
                'listId' => 'introduction',
                'email' => self::USERNAME,
            ]
        ));

        $this->assertEmpty(
            $flashBag->get(InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY)
        );

        $this->assertEmpty(
            $flashBag->get(InviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY)
        );

        if ($expectedHasUserCookie) {
            /* @var Cookie[] $cookies */
            $cookies = $response->headers->getCookies();
            $cookie = $cookies[0];

            $this->assertInstanceOf(Cookie::class, $cookie);
            $this->assertEquals('simplytestable-user', $cookie->getName());
        } else {
            $this->assertEmpty($response->headers->getCookies());
        }
    }

    public function acceptActionSuccessDataProvider(): array
    {
        return [
            'stay-signed-in not present' => [
                'request' => new Request([], [
                    'password' => self::PASSWORD,
                ]),
                'expectedHasUserCookie' => false,
            ],
            'stay-signed-in: 1' => [
                'request' => new Request([], [
                    'password' => self::PASSWORD,
                    'stay-signed-in' => 1,
                ]),
                'expectedHasUserCookie' => true,
            ],
        ];
    }
}
