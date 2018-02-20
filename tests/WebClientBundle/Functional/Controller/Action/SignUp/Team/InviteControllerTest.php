<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class InviteControllerTest extends AbstractBaseTestCase
{
    const USERNAME = 'user@example.com';
    const TOKEN = 'tokenValue';
    const PASSWORD = 'passwordValue';

    /**
     * @var InviteController
     */
    private $inviteController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->inviteController = new InviteController();
        $this->inviteController->setContainer($this->container);
    }

    public function testAcceptActionPostRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => 'Team Name',
                'user' => self::USERNAME,
                'token' => self::TOKEN,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('action_signup_team_invite_accept', [
            'token' => self::TOKEN,
        ]);

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/'));
    }

    /**
     * @dataProvider acceptActionFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $token
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param array $expectedErrorFlashBagValues
     * @param array $expectedFailureFlashBagValues
     *
     * @throws \CredisException
     * @throws \Exception
     */
    public function testAcceptActionFailure(
        array $httpFixtures,
        $token,
        Request $request,
        $expectedRedirectUrl,
        array $expectedErrorFlashBagValues,
        array $expectedFailureFlashBagValues
    ) {
        $session = $this->container->get('session');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->inviteController->acceptAction($request, $token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $this->assertEquals(
            $expectedErrorFlashBagValues,
            $session->getFlashBag()->get(InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY)
        );

        $this->assertEquals(
            $expectedFailureFlashBagValues,
            $session->getFlashBag()->get(InviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY)
        );
    }

    /**
     * @return array
     */
    public function acceptActionFailureDataProvider()
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
                'expectedRedirectUrl' => 'http://localhost/signup/',
                'expectedErrorFlashBagValues' => [],
                'expectedFailureFlashBagValues' => [],
            ],
            'invalid token' => [
                'httpFixtures' => [
                    $notFoundResponse,
                ],
                'token' => 'invalid token',
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/signup/',
                'expectedErrorFlashBagValues' => [],
                'expectedFailureFlashBagValues' => [],
            ],
            'missing password' => [
                'httpFixtures' => [
                    $inviteHttpResponse,
                ],
                'token' => self::TOKEN,
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/signup/invite/tokenValue/',
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
                'expectedRedirectUrl' => 'http://localhost/signup/invite/tokenValue/',
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
                'expectedRedirectUrl' => 'http://localhost/signup/invite/tokenValue/',
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
     *
     * @param Request $request
     * @param bool $expectedHasUserCookie
     *
     * @throws \CredisException
     * @throws \Exception
     */
    public function testAcceptActionSuccess(
        Request $request,
        $expectedHasUserCookie
    ) {
        $session = $this->container->get('session');
        $resqueQueueService = $this->container->get('SimplyTestable\WebClientBundle\Services\Resque\QueueService');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures([
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
        $this->assertEquals('http://localhost/', $response->getTargetUrl());

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
            $session->getFlashBag()->get(InviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY)
        );

        $this->assertEmpty(
            $session->getFlashBag()->get(InviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY)
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

    /**
     * @return array
     */
    public function acceptActionSuccessDataProvider()
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
