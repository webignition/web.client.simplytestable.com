<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\NewsSubscriptionsController;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\PasswordChangeController;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PasswordChangeControllerTest extends BaseSimplyTestableTestCase
{
    const USER_EMAIL = 'user@example.com';
    const USER_CURRENT_PASSWORD = 'current-password';

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
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 404 Not Found'),
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
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($userService->getPublicUser());

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200 OK'),
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
        $userService = $this->container->get('simplytestable.services.userservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userService->setUser($user);

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200 OK'),
            HttpResponseFactory::createJsonResponse('token-value'),
            Response::fromMessage('HTTP/1.1 200'),
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
     */
    public function testRequestAction(
        array $httpFixtures,
        Request $request,
        array $expectedFlashBagValues,
        $expectedUserPassword
    ) {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = new User(self::USER_EMAIL, self::USER_CURRENT_PASSWORD);
        $userService->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        /* @var RedirectResponse $response */
        $response = $this->passwordChangeController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());

        $this->assertEquals(
            $expectedFlashBagValues,
            $session->getFlashBag()->get(PasswordChangeController::FLASH_BAG_REQUEST_KEY)
        );

        $this->assertEquals($expectedUserPassword, $userService->getUser()->getPassword());
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
                    Response::fromMessage('HTTP/1.1 503'),
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
                    Response::fromMessage('HTTP/1.1 404'),
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
                    CurlExceptionFactory::create('Operation timed out', 28),
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
                    Response::fromMessage('HTTP/1.1 200'),
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
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'request' => new Request([], [
                    'current-password' => self::USER_CURRENT_PASSWORD,
                    'new-password' => 'foo',
                ], [], [
                    UserService::USER_COOKIE_KEY => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    PasswordChangeController::FLASH_BAG_REQUEST_SUCCESS_MESSAGE,
                ],
                'expectedUserPassword' => 'foo',
            ],
        ];
    }

//
//    /**
//     * @dataProvider updateActionDataProvider
//     *
//     * @param array $httpFixtures
//     * @param User $user
//     * @param array $existingListRecipients
//     * @param Request $request
//     * @param array $expectedFlashBagValues
//     * @param bool $expectedAnnouncementsListRecipientsContains
//     * @param bool $expectedUpdatesListRecipientsContains
//     */
//    public function testUpdateAction(
//        array $httpFixtures,
//        User $user,
//        array $existingListRecipients,
//        Request $request,
//        array $expectedFlashBagValues,
//        $expectedAnnouncementsListRecipientsContains,
//        $expectedUpdatesListRecipientsContains
//    ) {
//        $userService = $this->container->get('simplytestable.services.userservice');
//        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
//        $session = $this->container->get('session');
//
//        /* @var EntityManagerInterface $entityManager */
//        $entityManager = $this->container->get('doctrine.orm.entity_manager');
//
//        $httpMockPlugin = new MockPlugin($httpFixtures);
//
//        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
//        $mailChimpClient->addSubscriber($httpMockPlugin);
//
//        $userService->setUser($user);
//
//        /* @var ListRecipients[] $listRecipientsCollection */
//        $listRecipientsCollection = [];
//
//        foreach ($existingListRecipients as $listName => $recipients) {
//            $listRecipients = new ListRecipients();
//            $listRecipients->setListId($mailChimpListRecipientsService->getListId($listName));
//
//            foreach ($recipients as $recipient) {
//                $listRecipients->addRecipient($recipient);
//            }
//
//            $entityManager->persist($listRecipients);
//            $entityManager->flush();
//
//            $listRecipientsCollection[$listName] = $listRecipients;
//        }
//
//        $this->container->set('request', $request);
//
//        /* @var RedirectResponse $response */
//        $response = $this->passwordChangeController->updateAction();
//
//        $this->assertInstanceOf(RedirectResponse::class, $response);
//        $this->assertTrue($response->isRedirect('http://localhost/account/#news-subscriptions'));
//
//        $this->assertEquals(
//            $expectedFlashBagValues,
//            $session->getFlashBag()->get('user_account_newssubscriptions_update')
//        );
//
//        $this->assertEquals(
//            $expectedAnnouncementsListRecipientsContains,
//            $listRecipientsCollection['announcements']->contains($user->getUsername())
//        );
//
//        $this->assertEquals(
//            $expectedUpdatesListRecipientsContains,
//            $listRecipientsCollection['updates']->contains($user->getUsername())
//        );
//    }
//
//    /**
//     * @return array
//     */
//    public function updateActionDataProvider()
//    {
//        return [
//            'no request data, no existing recipients' => [
//                'httpFixtures' => [],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [],
//                    'updates' => [],
//                ],
//                'request' => new Request([], []),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'already-unsubscribed',
//                    'updates' => 'already-unsubscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//            'no request data, not existing recipient' => [
//                'httpFixtures' => [],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [
//                        'foo@example.com',
//                    ],
//                    'updates' => [
//                        'foo@example.com',
//                    ],
//                ],
//                'request' => new Request([], []),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'already-unsubscribed',
//                    'updates' => 'already-unsubscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//            'no request data, is existing announcements recipient' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                ],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [
//                        'user@example.com',
//                    ],
//                    'updates' => [],
//                ],
//                'request' => new Request([], []),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'unsubscribed',
//                    'updates' => 'already-unsubscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//            'no request data, is existing updates recipient' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                ],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [],
//                    'updates' => [
//                        'user@example.com',
//                    ],
//                ],
//                'request' => new Request([], []),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'already-unsubscribed',
//                    'updates' => 'unsubscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//            'request to subscribe to both, no existing recipients' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                ],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [],
//                    'updates' => [],
//                ],
//                'request' => new Request([], [
//                    'announcements' => true,
//                    'updates' => true,
//                ]),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'subscribed',
//                    'updates' => 'subscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => true,
//                'expectedUpdatesListRecipientsContains' => true,
//            ],
//            'request to unsubscribe from both, is existing recipient of both' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                    Response::fromMessage('HTTP/1.1 200 OK'),
//                ],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [
//                        'user@example.com'
//                    ],
//                    'updates' => [
//                        'user@example.com'
//                    ],
//                ],
//                'request' => new Request([], [
//                    'announcements' => false,
//                    'updates' => false,
//                ]),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'unsubscribed',
//                    'updates' => 'unsubscribed',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//            'request to subscribe to both, import exceptions, no existing recipients' => [
//                'httpFixtures' => [
//                    Response::fromMessage("HTTP/1.1 400 Bad Request\nContent-Type:application/json\n\n" . json_encode([
//                            'name' => 'List_InvalidImport',
//                            'code' => 220,
//                            'error' => '',
//                        ])),
//                    Response::fromMessage("HTTP/1.1 400 Bad Request\nContent-Type:application/json\n\n" . json_encode([
//                            'name' => 'List_InvalidImport',
//                            'code' => 100,
//                            'error' => '',
//                        ])),
//                ],
//                'user' => new User('user@example.com', 'password'),
//                'existingListRecipients' => [
//                    'announcements' => [],
//                    'updates' => [],
//                ],
//                'request' => new Request([], [
//                    'announcements' => true,
//                    'updates' => true,
//                ]),
//                'expectedFlashBagValues' => [
//                    'announcements' => 'subscribe-failed-banned',
//                    'updates' => 'subscribe-failed-unknown',
//                ],
//                'expectedAnnouncementsListRecipientsContains' => false,
//                'expectedUpdatesListRecipientsContains' => false,
//            ],
//        ];
//    }
}
