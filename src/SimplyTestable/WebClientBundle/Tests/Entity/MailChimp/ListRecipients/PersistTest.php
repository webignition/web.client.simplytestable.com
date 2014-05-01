<?php

namespace SimplyTestable\WebClientBundle\Tests\Entity\MailChimp\ListRecipients;

class PersistTest extends EntityTest {   
    
    public function testPersistWithNoPropertiesSetThrowsIntegrityConstraintViolation() {
        try {
            $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
            $this->container->get('doctrine')->getManager()->flush();            
            $this->fail('\Doctrine\DBAL\DBALException not thrown');
        } catch (\Doctrine\DBAL\DBALException $dbalException) {
            $this->assertTrue(substr_count($dbalException->getMessage(), 'INSERT INTO ListRecipients') > 0, 'DBALException does not relate to inserting ListRecipients entity');
            $this->assertTrue(substr_count($dbalException->getMessage(), 'ListRecipients.listId may not be NULL') > 0, 'DBALException does not relate to ListRecipients.listId not being allowed to be NULL');            
        }
    }    
    
    public function testPersistWithListIdOnly() {        
        $this->listRecipients->setListId('foo');
        
        $this->container->get('doctrine')->getManager()->persist($this->listRecipients);
        $this->container->get('doctrine')->getManager()->flush();

        $this->assertNotNull($this->listRecipients->getId());
    }
    
    
    public function testPersistWithListIdAndRecipients() {
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
