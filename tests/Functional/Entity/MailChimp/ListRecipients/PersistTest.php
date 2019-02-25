<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Entity\MailChimp\ListRecipients;

use App\Entity\MailChimp\ListRecipients;
use App\Tests\Functional\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

class PersistTest extends AbstractBaseTestCase
{
    /**
     * @var ListRecipients
     */
    private $listRecipients;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp()
    {
        parent::setUp();

        $this->listRecipients = new ListRecipients();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    /**
     * @dataProvider persistDataProvider
     */
    public function testPersist(string $listId, array $recipients)
    {
        $this->listRecipients->setListId($listId);

        if (!empty($recipients)) {
            $this->listRecipients->setRecipients($recipients);
        }

        $this->entityManager->persist($this->listRecipients);
        $this->entityManager->flush();

        $this->entityManager->clear();

        $retrievedListRecipients = $this->entityManager->getRepository(ListRecipients::class)->find($listId);

        $this->assertEquals($this->listRecipients, $retrievedListRecipients);
    }

    public function persistDataProvider(): array
    {
        return [
            'listId only' => [
                'listId' => 'list-id',
                'recipients' => [],
            ],
            'listId and recipients' => [
                'listId' => 'list-id',
                'recipients' => [
                    'user1@example.com',
                    'user2@example.com',
                    'user3@example.com',
                ],
            ],
        ];
    }
}
