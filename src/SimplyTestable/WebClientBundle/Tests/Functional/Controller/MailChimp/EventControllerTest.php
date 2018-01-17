<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\MailChimp;

use SimplyTestable\WebClientBundle\Controller\MailChimp\EventController;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\Request;

class EventControllerTest extends BaseSimplyTestableTestCase
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

        $this->eventController = new EventController();
        $this->eventController->setContainer($this->container);
    }

    public function testIndexActionGetRequest()
    {
        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param Request $request
     */
    public function testIndexActionBadRequest(Request $request)
    {
        $this->eventController->setContainer($this->container);
        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isClientError());
    }

    /**
     * @return array
     */
    public function indexActionBadRequestDataProvider()
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
     *
     * @param Request $request
     * @param string $listId
     * @param string[] $existingListRecipients
     * @param string[] $expectedListRecipients
     */
    public function testIndexActionSuccess(
        Request $request,
        $listId,
        array $existingListRecipients,
        array $expectedListRecipients
    ) {
        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $listName = $mailChimpListRecipientsService->getListName($listId);
        $list = $mailChimpListRecipientsService->get($listName);

        if (!empty($existingListRecipients)) {
            foreach ($existingListRecipients as $listRecipient) {
                $list->addRecipient($listRecipient);
            }

            $entityManager->persist($list);
            $entityManager->flush();
        }

        $list = $mailChimpListRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $list->getRecipients());

        $request->setMethod('POST');

        $response = $this->eventController->indexAction($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(
            array_values($expectedListRecipients),
            array_values($mailChimpListRecipientsService->get($listName)->getRecipients())
        );
    }

    /**
     * @return array
     */
    public function indexActionSuccessDataProvider()
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
