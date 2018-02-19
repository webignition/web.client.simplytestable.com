<?php

namespace Tests\WebClientBundle\Functional\Entity\MailChimp\ListRecipients;

use Doctrine\DBAL\DBALException;

class PersistTest extends EntityTest
{
    public function testPersistWithNoPropertiesSetThrowsIntegrityConstraintViolation()
    {
        try {
            $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
            $this->container->get('doctrine')->getManager()->flush();
            $this->fail('\Doctrine\DBAL\DBALException not thrown');
        } catch (DBALException $dbalException) {
            $this->assertTrue(
                substr_count($dbalException->getMessage(), 'INSERT INTO ListRecipients') > 0,
                'DBALException does not relate to inserting ListRecipients entity'
            );
            $this->assertDBALExceptionRelatesToListRecipientsListIdNotBeingAllowedToBeNull($dbalException);
        }
    }

    private function assertDBALExceptionRelatesToListRecipientsListIdNotBeingAllowedToBeNull(
        DBALException $dbalException
    ) {
        $exceptionMessageFragments = array(
            'Integrity constraint violation: 19 ListRecipients.listId may not be NULL', // SQLite variant
            'Integrity constraint violation: 1048 Column \'listId\' cannot be null'     // MySQL variant
        );

        foreach ($exceptionMessageFragments as $exceptionMessageFragment) {
            if (substr_count($dbalException->getMessage(), $exceptionMessageFragment)) {
                return true;
            }
        }

        $this->fail('DBALException does not relate to ListRecipients.listId not being allowed to be NULL');
    }

    public function testPersistWithListIdOnly()
    {
        $this->listRecipients->setListId('foo');

        $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
        $this->container->get('doctrine')->getManager()->flush();

        $this->assertNotNull($this->listRecipients->getId());
    }

    public function testPersistWithListIdAndRecipients()
    {
        $this->listRecipients->setListId('foo');
        $this->listRecipients->setRecipients(array(
            'foobar1',
            'foobar2',
            'foobar3'
        ));

        $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
        $this->container->get('doctrine')->getManager()->flush();

        $this->assertNotNull($this->listRecipients->getId());
    }
}
