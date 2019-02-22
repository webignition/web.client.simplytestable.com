<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MailChimp\ListRecipients;
use App\Services\MailChimp\ListRecipientsService;
use App\Tests\Functional\AbstractBaseTestCase;

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

        $this->listRecipientsService = self::$container->get(ListRecipientsService::class);
    }

    /**
     * @dataProvider hasListIdentifierDataProvider
     */
    public function testHasListIdentifier(string $name, bool $expectedHasListIdentifier)
    {
        $this->assertEquals(
            $expectedHasListIdentifier,
            $this->listRecipientsService->hasListIdentifier($name)
        );
    }

    public function hasListIdentifierDataProvider(): array
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
     */
    public function testGetListIdSuccess(string $name, string $expectedListId)
    {
        $this->assertEquals($expectedListId, $this->listRecipientsService->getListId($name));
    }

    public function getListIdDataProvider(): array
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
     */
    public function testGetListNameSuccess(string $listId, string $expectedListName)
    {
        $this->assertEquals($expectedListName, $this->listRecipientsService->getListName($listId));
    }

    public function getListNameDataProvider(): array
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
     */
    public function testGetNew(string $name)
    {
        $listRecipients = $this->listRecipientsService->get($name);

        $this->assertInstanceOf(ListRecipients::class, $listRecipients);

        $this->assertEquals([], $listRecipients->getRecipients());
    }

    public function getNewDataProvider(): array
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
     */
    public function testGetExisting(array $recipients, string $name)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

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

    public function getExistingDataProvider(): array
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
