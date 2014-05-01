<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\MailChimp\Service;

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
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    protected function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }   
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    protected function getMailChimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    }        

}
