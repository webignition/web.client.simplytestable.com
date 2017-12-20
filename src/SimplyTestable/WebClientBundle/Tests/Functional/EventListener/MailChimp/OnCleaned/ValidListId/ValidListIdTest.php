<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnCleaned\ValidListId;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnCleaned\OnCleanedTest;

abstract class ValidListIdTest extends OnCleanedTest {

    /**
     *
     * @var string
     */
    private $email = 'user@example.com';

    protected function setUp() {
        parent::setUp();

        $listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
        $listRecipients->addRecipient($this->email);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->assertTrue($listRecipients->contains($this->email));

        $this->callListener(array(
            'data' => array(
                'list_id' => $this->getMailChimpListRecipientsService()->getListId($this->getListName()),
                'email' => $this->email
            )
        ));
    }


    public function testEmailAddressIsRemovedFromListReceipients() {
        $this->assertFalse($this->getMailChimpListRecipientsService()->get($this->getListName())->contains($this->email));
    }


    /**
     * Derive list name from test namespace
     *
     * @return string
     */
    protected function getListName() {
        $classNameParts = explode('\\', get_class($this));
        return strtolower(str_replace('Test', '', $classNameParts[count($classNameParts) - 1]));
    }

}