<?php

namespace Tests\WebClientBundle\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class ListRecipientsServiceTest extends AbstractBaseTestCase
{
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

        $this->listRecipientsService = $this->container->get('SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService');
    }

    /**
     * @dataProvider hasListIdentifierDataProvider
     *
     * @param string $name
     * @param bool $expectedHasListIdentifier
     */
    public function testHasListIdentifier($name, $expectedHasListIdentifier)
    {
        $this->assertEquals(
            $expectedHasListIdentifier,
            $this->listRecipientsService->hasListIdentifier($name)
        );
    }

    /**
     * @return array
     */
    public function hasListIdentifierDataProvider()
    {
        return [
            'has announcements' => [
                'name' => 'announcements',
                'expectedHasListIdentifier' => true,
            ],
            'has updates' => [
                'name' => 'updates',
                'expectedHasListIdentifier' => true,
            ],
            'has introduction' => [
                'name' => 'introduction',
                'expectedHasListIdentifier' => true,
            ],
            'not has foo' => [
                'name' => 'foo',
                'expectedHasListIdentifier' => false,
            ],
        ];
    }

    public function testGetListIdException()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('List "foo" is not known');
        $this->expectExceptionCode(ListRecipientsService::EXCEPTION_LIST_NOT_KNOWN_CODE);

        $this->listRecipientsService->getListId('foo');
    }

    /**
     * @dataProvider getListIdDataProvider
     *
     * @param string $name
     * @param string $expectedListId
     */
    public function testGetListIdSuccess($name, $expectedListId)
    {
        $this->assertEquals($expectedListId, $this->listRecipientsService->getListId($name));
    }

    /**
     * @return array
     */
    public function getListIdDataProvider()
    {
        return [
            'announcements' => [
                'name' => 'announcements',
                'expectedListId' => '1224633c43',
            ],
            'updates' => [
                'name' => 'updates',
                'expectedListId' => '311aedc7f4',
            ],
            'introduction' => [
                'name' => 'introduction',
                'expectedListId' => 'e1b78ec47a',
            ],
        ];
    }

    public function testGetListNameException()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('List id "foo" is not known');
        $this->expectExceptionCode(ListRecipientsService::EXCEPTION_LIST_ID_NOT_KNOWN_CODE);

        $this->listRecipientsService->getListName('foo');
    }

    /**
     * @dataProvider getListNameDataProvider
     *
     * @param string $listId
     * @param string $expectedListName
     */
    public function testGetListNameSuccess($listId, $expectedListName)
    {
        $this->assertEquals($expectedListName, $this->listRecipientsService->getListName($listId));
    }

    /**
     * @return array
     */
    public function getListNameDataProvider()
    {
        return [
            'announcements' => [
                'listId' => '1224633c43',
                'expectedListName' => 'announcements',
            ],
            'updates' => [
                'listId' => '311aedc7f4',
                'expectedListName' => 'updates',
            ],
            'introduction' => [
                'listId' => 'e1b78ec47a',
                'expectedListName' => 'introduction',
            ],
        ];
    }

    public function testGetException()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('List "foo" is not known');
        $this->expectExceptionCode(ListRecipientsService::EXCEPTION_LIST_NOT_KNOWN_CODE);

        $this->listRecipientsService->get('foo');
    }

    /**
     * @dataProvider getNewDataProvider
     *
     * @param string $name
     */
    public function testGetNew($name)
    {
        $listRecipients = $this->listRecipientsService->get($name);

        $this->assertInstanceOf(ListRecipients::class, $listRecipients);

        $this->assertEquals([], $listRecipients->getRecipients());
    }

    /**
     * @return array
     */
    public function getNewDataProvider()
    {
        return [
            'announcements' => [
                'name' => 'announcements',
            ],
            'updates' => [
                'name' => 'updates',
            ],
            'introduction' => [
                'name' => 'introduction',
            ],
        ];
    }

    /**
     * @dataProvider getExistingDataProvider
     *
     * @param string[] $recipients
     * @param string $name
     */
    public function testGetExisting($recipients, $name)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId($name));
        foreach ($recipients as $recipient) {
            $listRecipients->addRecipient($recipient);
        }

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $retrievedListRecipients = $this->listRecipientsService->get($name);

        $this->assertInstanceOf(ListRecipients::class, $listRecipients);

        $this->assertEquals($recipients, $retrievedListRecipients->getRecipients());
    }

    /**
     * @return array
     */
    public function getExistingDataProvider()
    {
        return [
            'announcements' => [
                'recipients' => [
                    'foo@example.com',
                ],
                'name' => 'announcements',
            ],
            'updates' => [
                'recipients' => [
                    'bar@example.com',
                ],
                'name' => 'updates',
            ],
            'introduction' => [
                'recipients' => [
                    'foobar1@example.com',
                    'foobar2@example.com',
                ],
                'name' => 'introduction',
            ],
        ];
    }
}
