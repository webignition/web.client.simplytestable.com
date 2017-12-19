<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseTestCase;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class ListenerTest extends BaseTestCase {
    
    public function setUp() {
        parent::setUp();
        $entities = $this->getMailChimpListRecipientsService()->getEntityRepository()->findAll();
        
        foreach ($entities as $entity) {            
            $this->getMailChimpListRecipientsService()->removeList($entity->getListId());
        }
    }    
    
    abstract protected function getEventType();
    abstract protected function getListenerMethodName();    
    
    protected function callListener($data) {
        $data['type'] = $this->getEventType();     
        
        $methodName = $this->getListenerMethodName();
        $this->getListener()->$methodName(new MailChimpEvent(new ParameterBag($data)));
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\EventListener\MailChimp\Listener
     */
    protected function getListener() {
        return $this->container->get('simplytestable.listener.mailchimpEvent');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    protected function getMailChimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    } 
    
}