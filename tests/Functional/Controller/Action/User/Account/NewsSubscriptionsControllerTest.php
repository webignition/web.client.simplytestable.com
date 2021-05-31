<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Action\User\Account\NewsSubscriptionsController;
use App\Entity\MailChimp\ListRecipients;
use App\Model\MailChimp\ApiError;
use App\Services\MailChimp\ListRecipientsService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;

class NewsSubscriptionsControllerTest extends AbstractUserAccountControllerTest
{
    const ROUTE_NAME = 'action_user_account_news_subscriptions_update';

    /**
     * @var NewsSubscriptionsController
     */
    private $newsSubscriptionsController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->newsSubscriptionsController = self::$container->get(NewsSubscriptionsController::class);
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

    public function testUpdateActionInvalidUserPostRequest()
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

    /**
     * @dataProvider updateActionDataProvider
     */
    public function testUpdateAction(
        array $httpFixtures,
        User $user,
        array $existingListRecipients,
        Request $request,
        array $expectedFlashBagValues,
        bool $expectedAnnouncementsListRecipientsContains,
        bool $expectedUpdatesListRecipientsContains
    ) {
        $mailChimpListRecipientsService = self::$container->get(ListRecipientsService::class);
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $userManager->setUser($user);

        /* @var ListRecipients[] $listRecipientsCollection */
        $listRecipientsCollection = [];

        foreach ($existingListRecipients as $listName => $recipients) {
            $listRecipients = ListRecipients::create(
                $mailChimpListRecipientsService->getListId($listName),
                $recipients
            );

            $entityManager->persist($listRecipients);
            $entityManager->flush();

            $listRecipientsCollection[$listName] = $listRecipients;
        }

        /* @var RedirectResponse $response */
        $response = $this->newsSubscriptionsController->updateAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/#news-subscriptions', $response->getTargetUrl());

        $this->assertEquals(
            $expectedFlashBagValues,
            $flashBag->get('user_account_newssubscriptions_update')
        );

        $this->assertEquals(
            $expectedAnnouncementsListRecipientsContains,
            $listRecipientsCollection['announcements']->contains($user->getUsername())
        );

        $this->assertEquals(
            $expectedUpdatesListRecipientsContains,
            $listRecipientsCollection['updates']->contains($user->getUsername())
        );
    }

    public function updateActionDataProvider(): array
    {
        return [
            'no request data, no existing recipients' => [
                'httpFixtures' => [],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [],
                    'updates' => [],
                ],
                'request' => new Request([], []),
                'expectedFlashBagValues' => [
                    'announcements' => 'already-unsubscribed',
                    'updates' => 'already-unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'no request data, not existing recipient' => [
                'httpFixtures' => [],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [
                        'foo@example.com',
                    ],
                    'updates' => [
                        'foo@example.com',
                    ],
                ],
                'request' => new Request([], []),
                'expectedFlashBagValues' => [
                    'announcements' => 'already-unsubscribed',
                    'updates' => 'already-unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'no request data, is existing announcements recipient' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [
                        'user@example.com',
                    ],
                    'updates' => [],
                ],
                'request' => new Request([], []),
                'expectedFlashBagValues' => [
                    'announcements' => 'unsubscribed',
                    'updates' => 'already-unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'no request data, is existing updates recipient' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [],
                    'updates' => [
                        'user@example.com',
                    ],
                ],
                'request' => new Request([], []),
                'expectedFlashBagValues' => [
                    'announcements' => 'already-unsubscribed',
                    'updates' => 'unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'request to subscribe to both, no existing recipients' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [],
                    'updates' => [],
                ],
                'request' => new Request([], [
                    'announcements' => true,
                    'updates' => true,
                ]),
                'expectedFlashBagValues' => [
                    'announcements' => 'subscribed',
                    'updates' => 'subscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => true,
                'expectedUpdatesListRecipientsContains' => true,
            ],
            'request to unsubscribe from both, is existing recipient of both' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [
                        'user@example.com'
                    ],
                    'updates' => [
                        'user@example.com'
                    ],
                ],
                'request' => new Request([], [
                    'announcements' => false,
                    'updates' => false,
                ]),
                'expectedFlashBagValues' => [
                    'announcements' => 'unsubscribed',
                    'updates' => 'unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'request to subscribe to both, member exists and unknown exceptions, no existing recipients' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([], json_encode([
                        'title' => ApiError::TITLE_MEMBER_EXISTS,
                        'detail' => 'foo'
                    ])),
                    HttpResponseFactory::createNotFoundResponse([], json_encode([
                        'title' => ApiError::TITLE_RESOURCE_NOT_FOUND,
                        'detail' => 'The requested resource could not be found.'
                    ])),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [],
                    'updates' => [],
                ],
                'request' => new Request([], [
                    'announcements' => true,
                    'updates' => true,
                ]),
                'expectedFlashBagValues' => [
                    'announcements' => 'subscribe-failed-unknown',
                    'updates' => 'subscribe-failed-unknown',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
            'request to unsubscribe from both, not found and unknown exception' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse([], json_encode([
                        'title' => 'foo',
                        'detail' => 'foo'
                    ])),
                    HttpResponseFactory::createNotFoundResponse([], json_encode([
                        'title' => ApiError::TITLE_RESOURCE_NOT_FOUND,
                        'detail' => 'The requested resource could not be found.'
                    ])),
                ],
                'user' => new User('user@example.com', 'password'),
                'existingListRecipients' => [
                    'announcements' => [
                        'user@example.com'
                    ],
                    'updates' => [
                        'user@example.com'
                    ],
                ],
                'request' => new Request([], [
                    'announcements' => false,
                    'updates' => false,
                ]),
                'expectedFlashBagValues' => [
                    'announcements' => 'unsubscribed',
                    'updates' => 'unsubscribed',
                ],
                'expectedAnnouncementsListRecipientsContains' => false,
                'expectedUpdatesListRecipientsContains' => false,
            ],
        ];
    }
}
