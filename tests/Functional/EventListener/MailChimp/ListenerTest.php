<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MailChimp\ListRecipients;
use App\EventListener\MailChimp\Listener;
use App\Services\MailChimp\ListRecipientsService;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\EventListener\MailChimp\Listener as MailChimpListener;

class ListenerTest extends AbstractBaseTestCase
{
    const LIST_NAME = 'announcements';
    const LIST_ID = '1224633c43';

    /**
     * @var Listener
     */
    private $listener;

    /**
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->listener = self::$container->get(MailChimpListener::class);
        $this->listRecipientsService = self::$container->get(ListRecipientsService::class);
    }

    /**
     * @dataProvider onSubscribeDataProvider
     */
    public function testOnSubscribe(MailChimpEvent $event, array $expectedListRecipients = [])
    {
        $retrievedListRecipients = $this->listRecipientsService->get(self::LIST_NAME);
        $this->assertEquals([], $retrievedListRecipients->getRecipients());

        $this->listener->onSubscribe($event);

        $retrievedListRecipients = $this->listRecipientsService->get(self::LIST_NAME);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    public function onSubscribeDataProvider(): array
    {
        return [
            'no event email' => [
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'has event email' => [
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [
                    'user@example.com',
                ],
            ],
        ];
    }

    /**
     * @dataProvider onUnSubscribeDataProvider
     */
    public function testOnUnSubscribe(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = self::$container->get(ListRecipientsService::class);

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onUnsubscribe($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    public function onUnSubscribeDataProvider(): array
    {
        return [
            'no existing recipients, no event email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'existing recipients, no event email' => [
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [
                    'user@example.com',
                ],
            ],
            'existing recipients, has event email' => [
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
        ];
    }

    /**
     * @dataProvider onUpEmailDataProvider
     */
    public function testOnUpEmail(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = self::$container->get(ListRecipientsService::class);

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onUpEmail($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, array_values($retrievedListRecipients->getRecipients()));
    }

    public function onUpEmailDataProvider(): array
    {
        return [
            'no existing recipients, no event old_email, no event new_email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'no existing recipients, has event old_email, no event new_email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                        'old_email' => 'user@example.com',
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'no existing recipients, no event old_email, has event new_email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                        'new_email' => 'new-user@example.com',
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'no existing recipients, has event old_email, has event new_email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                        'old_email' => 'user@example.com',
                        'new_email' => 'new-user@example.com',
                    ],
                ])),
                'expectedListRecipients' => [
                    'new-user@example.com',
                ],
            ],
            'has existing recipients, has event old_email, has event new_email' => [
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                        'old_email' => 'user@example.com',
                        'new_email' => 'new-user@example.com',
                    ],
                ])),
                'expectedListRecipients' => [
                    'new-user@example.com',
                ],
            ],
        ];
    }

    /**
     * @dataProvider onCleanedDataProvider
     */
    public function testOnCleaned(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = self::$container->get(ListRecipientsService::class);

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onCleaned($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    public function onCleanedDataProvider(): array
    {
        return [
            'no existing recipients, no event email' => [
                'existingListRecipients' => [],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
            'existing recipients, no event email' => [
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [
                    'user@example.com',
                ],
            ],
            'existing recipients, has event email' => [
                'existingListRecipients' => [
                    'user@example.com',
                ],
                'event' => new MailChimpEvent(new ParameterBag([
                    'data' => [
                        'email' => 'user@example.com',
                        'list_id' => self::LIST_ID,
                    ],
                ])),
                'expectedListRecipients' => [],
            ],
        ];
    }

    private function createExistingListRecipients(array $existingListRecipients)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $listRecipients = ListRecipients::create(
            $this->listRecipientsService->getListId(self::LIST_NAME),
            $existingListRecipients
        );

        $entityManager->persist($listRecipients);
        $entityManager->flush();
    }
}
