<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\MailChimp\ListRecipients;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

abstract class ServiceTest extends BaseSimplyTestableTestCase {
    
    public function setUp() {
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
