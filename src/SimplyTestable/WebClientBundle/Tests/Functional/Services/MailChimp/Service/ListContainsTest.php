<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\Service;

use Doctrine\ORM\EntityManagerInterface;

class ListContainsTest extends ServiceTest {


    public function testListDoesContain() {
        $email = 'user@example.com';
        $listName = 'updates';

        $listRecipients = $this->getMailChimpListRecipientsService()->get($listName);
        $listRecipients->addRecipient($email);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->assertTrue($this->getMailchimpService()->listContains($listName, $email));
    }


    public function testListDoesNotContain() {
        $this->assertFalse($this->getMailchimpService()->listContains('updates', 'foo@bar.com'));
    }

}
