<?php

namespace Tests\WebClientBundle\Functional\Entity\MailChimp\ListRecipients;

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

        $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
        $this->container->get('doctrine')->getManager()->flush();

        $this->entityId = $this->listRecipients->getId();

        $this->container->get('doctrine')->getManager()->clear();

        $entityManager = $this->container->get('doctrine.orm.entity_manager');

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