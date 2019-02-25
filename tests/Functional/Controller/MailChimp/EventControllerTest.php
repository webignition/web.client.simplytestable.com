<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\MailChimp;

use App\Tests\Services\ObjectReflector;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\MailChimp\EventController;
use App\Services\MailChimp\ListRecipientsService;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\Controller\AbstractControllerTest;

class EventControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'mailchimp_event';

    /**
     * @var EventController
     */
    private $eventController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->eventController = self::$container->get(EventController::class);
    }

    public function testIndexActionGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     */
    public function testIndexActionBadRequest(Request $request)
    {
        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isClientError());
    }

    public function indexActionBadRequestDataProvider(): array
    {
        return [
            'missing type' => [
                'request' => new Request(),
            ],
            'invalid type' => [
                'request' => new Request([], [
                    'type' => 'foo',
                ]),
            ],
        ];
    }

    public function testIndexActionUnexpectedValueExceptionFromListener()
    {
        $request = new Request([], [
            'type' => 'subscribe',
        ]);

        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isSuccessful());
    }


    public function testIndexActionDomainExceptionInvalidEventDataListIdFromListener()
    {
        $request = new Request([], [
            'type' => 'subscribe',
            'data' => [
                'email' => 'user@example.com',
                'list_id' => 'foo',
            ],
        ]);

        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionSuccessDataProvider
     */
    public function testIndexActionSuccess(
        Request $request,
        string $listId,
        array $existingListRecipients,
        array $expectedListRecipients
    ) {
        $mailChimpListRecipientsService = self::$container->get(ListRecipientsService::class);
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $listName = $mailChimpListRecipientsService->getListName($listId);
        $list = $mailChimpListRecipientsService->get($listName);

        if (!empty($existingListRecipients)) {
            $list->addRecipients($existingListRecipients);

            $entityManager->persist($list);
            $entityManager->flush();
        }

        $list = $mailChimpListRecipientsService->get($listName);
        $listRecipients = ObjectReflector::getProperty($list, 'recipients');

        $this->assertEquals($existingListRecipients, $listRecipients);

        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isSuccessful());

        $mutatedList = $mailChimpListRecipientsService->get($listName);
        $mutatedListRecipients = ObjectReflector::getProperty($mutatedList, 'recipients');

        $this->assertEquals(
            array_values($expectedListRecipients),
            array_values($mutatedListRecipients)
        );
    }

    public function indexActionSuccessDataProvider(): array
    {
        return [
            'subscribe' => [
                'request' => new Request([], [
                    'type' => 'subscribe',
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => 'e1b78ec47a',
                    ],
                ]),
                'listId' => 'e1b78ec47a',
                'existingListRecipients' => [],
                'expectedListRecipients' => [
                    'user@example.com',
                ],
            ],
            'unsubscribe' => [
                'request' => new Request([], [
                    'type' => 'unsubscribe',
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => 'e1b78ec47a',
                    ],
                ]),
                'listId' => 'e1b78ec47a',
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'expectedListRecipients' => [],
            ],
            'upemail' => [
                'request' => new Request([], [
                    'type' => 'upemail',
                    'data' => [
                        'old_email' => 'user@example.com',
                        'new_email' => 'foo@example.com',
                        'list_id' => 'e1b78ec47a',
                    ],
                ]),
                'listId' => 'e1b78ec47a',
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'expectedListRecipients' => [
                    'foo@example.com',
                ],
            ],
            'cleaned' => [
                'request' => new Request([], [
                    'type' => 'cleaned',
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => 'e1b78ec47a',
                    ],
                ]),
                'listId' => 'e1b78ec47a',
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'expectedListRecipients' => [],
            ],
        ];
    }
}
