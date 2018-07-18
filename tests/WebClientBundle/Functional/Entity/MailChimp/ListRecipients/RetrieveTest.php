<?php

namespace Tests\WebClientBundle\Functional\Entity\MailChimp\ListRecipients;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

class RetrieveTest extends EntityTest
{
    /**
     * @var string
     */
    private $listId = 'foo';

    /**
     * @var array
     */
    private $recipients = array(
            'foobar1',
            'foobar2',
            'foobar3'
    );

    /**
     * @var int
     */
    private $entityId;

    protected function setUp()
    {
        parent::setUp();

        $this->listRecipients->setListId($this->listId);
        $this->listRecipients->setRecipients($this->recipients);

        self::$container->get('doctrine')->getManager()->persist($this->listRecipients);
        self::$container->get('doctrine')->getManager()->flush();

        $this->entityId = $this->listRecipients->getId();

        self::$container->get('doctrine')->getManager()->clear();

        $entityManager = self::$container->get(EntityManagerInterface::class);

        $this->listRecipients = $entityManager->getRepository(ListRecipients::class)->find($this->entityId);
    }

    public function testListId()
    {
        $this->assertEquals($this->listId, $this->listRecipients->getListId());
    }

    public function testRecipients()
    {
        $this->assertEquals($this->recipients, $this->listRecipients->getRecipients());
    }
}
