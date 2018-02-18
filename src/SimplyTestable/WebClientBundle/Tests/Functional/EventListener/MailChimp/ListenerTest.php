<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\EventListener\MailChimp\Listener;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

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

        $this->listener = $this->container->get('simplytestable.listener.mailchimpevent');
        $this->listRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
    }

    /**
     * @dataProvider onSubscribeDataProvider
     *
     * @param MailChimpEvent $event
     * @param string[] $expectedListRecipients
     */
    public function testOnSubscribe(MailChimpEvent $event, array $expectedListRecipients = [])
    {
        $retrievedListRecipients = $this->listRecipientsService->get(self::LIST_NAME);
        $this->assertEquals([], $retrievedListRecipients->getRecipients());

        $this->listener->onSubscribe($event);

        $retrievedListRecipients = $this->listRecipientsService->get(self::LIST_NAME);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    /**
     * @return array
     */
    public function onSubscribeDataProvider()
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
     *
     * @param string[] $existingListRecipients
     * @param MailChimpEvent $event
     * @param string[] $expectedListRecipients
     */
    public function testOnUnSubscribe(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onUnsubscribe($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    /**
     * @return array
     */
    public function onUnSubscribeDataProvider()
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
     *
     * @param string[] $existingListRecipients
     * @param MailChimpEvent $event
     * @param string[] $expectedListRecipients
     */
    public function testOnUpEmail(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onUpEmail($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, array_values($retrievedListRecipients->getRecipients()));
    }

    /**
     * @return array
     */
    public function onUpEmailDataProvider()
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
     *
     * @param string[] $existingListRecipients
     * @param MailChimpEvent $event
     * @param string[] $expectedListRecipients
     */
    public function testOnCleaned(
        array $existingListRecipients,
        MailChimpEvent $event,
        array $expectedListRecipients = []
    ) {
        $this->createExistingListRecipients($existingListRecipients);

        $listRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');

        $listName = $listRecipientsService->getListName($event->getListId());

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($existingListRecipients, $retrievedListRecipients->getRecipients());

        $this->listener->onCleaned($event);

        $retrievedListRecipients = $listRecipientsService->get($listName);
        $this->assertEquals($expectedListRecipients, $retrievedListRecipients->getRecipients());
    }

    /**
     * @return array
     */
    public function onCleanedDataProvider()
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
     * @param array $existingListRecipients
     */
    private function createExistingListRecipients(array $existingListRecipients)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId(self::LIST_NAME));
        foreach ($existingListRecipients as $recipient) {
            $listRecipients->addRecipient($recipient);
        }

        $entityManager->persist($listRecipients);
        $entityManager->flush();
    }
}
