<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Subscriber\Mock as MockSubscriber;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\NewsSubscriptionsController;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Model\MailChimp\ApiError;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsSubscriptionsControllerTest extends AbstractUserAccountControllerTest
{
    const ROUTE_NAME = 'action_user_account_newssubscriptions_update';

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

        $this->newsSubscriptionsController = new NewsSubscriptionsController();
        $this->newsSubscriptionsController->setContainer($this->container);
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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    /**
     * @dataProvider updateActionDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param array $existingListRecipients
     * @param Request $request
     * @param array $expectedFlashBagValues
     * @param bool $expectedAnnouncementsListRecipientsContains
     * @param bool $expectedUpdatesListRecipientsContains
     */
    public function testUpdateAction(
        array $httpFixtures,
        User $user,
        array $existingListRecipients,
        Request $request,
        array $expectedFlashBagValues,
        $expectedAnnouncementsListRecipientsContains,
        $expectedUpdatesListRecipientsContains
    ) {
        $mailChimpListRecipientsService = $this->container->get(ListRecipientsService::class);
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $mockSubscriber = new MockSubscriber($httpFixtures);

        $mailChimpClient = $this->container->get(Client::class);
        $mailChimpClient->getHttpClient()->getEmitter()->attach($mockSubscriber);

        $userManager->setUser($user);

        /* @var ListRecipients[] $listRecipientsCollection */
        $listRecipientsCollection = [];

        foreach ($existingListRecipients as $listName => $recipients) {
            $listRecipients = new ListRecipients();
            $listRecipients->setListId($mailChimpListRecipientsService->getListId($listName));

            foreach ($recipients as $recipient) {
                $listRecipients->addRecipient($recipient);
            }

            $entityManager->persist($listRecipients);
            $entityManager->flush();

            $listRecipientsCollection[$listName] = $listRecipients;
        }

        /* @var RedirectResponse $response */
        $response = $this->newsSubscriptionsController->updateAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue($response->isRedirect('http://localhost/account/#news-subscriptions'));

        $this->assertEquals(
            $expectedFlashBagValues,
            $session->getFlashBag()->get('user_account_newssubscriptions_update')
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

    /**
     * @return array
     */
    public function updateActionDataProvider()
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
