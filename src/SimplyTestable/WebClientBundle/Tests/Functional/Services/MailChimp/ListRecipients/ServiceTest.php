<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class ServiceTest extends BaseSimplyTestableTestCase {
    
    protected function setUp() {
        parent::setUp();
        $entities = $this->getMailChimpListRecipientsService()->getEntityRepository()->findAll();
        
        foreach ($entities as $entity) {            
            $this->getMailChimpListRecipientsService()->removeList($entity->getListId());
        }
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    protected function getMailChimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    }    

}
