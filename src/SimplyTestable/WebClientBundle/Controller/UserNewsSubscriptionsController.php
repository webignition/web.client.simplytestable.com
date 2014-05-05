<?php

namespace SimplyTestable\WebClientBundle\Controller;

class UserNewsSubscriptionsController extends AbstractUserAccountController
{   
    const ONE_YEAR_IN_SECONDS = 31536000;    
    
    public function updateAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof \Symfony\Component\HttpFoundation\Response) {
            return $notLoggedInResponse;
        }
        
        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($this->get('request')->request->get($listName), FILTER_VALIDATE_BOOLEAN);
            
            if ($this->subscribeChoiceMatchesCurrentSubscription($listName, $this->getUser()->getUsername(), $subscribeChoice)) {
                continue;
            }
            
            $listRecipients = $this->getMailchimpListRecipientsService()->get($listName);
            
            if ($subscribeChoice === true) {
                $this->getMailchimpService()->subscribe($listName, $this->getUser()->getUsername()); 
                $listRecipients->addRecipient($this->getUser()->getUsername());
            } else {
                $this->getMailchimpService()->unsubscribe($listName, $this->getUser()->getUsername());
                $listRecipients->removeRecipient($this->getUser()->getUsername());
            }
            
            $this->getMailchimpListRecipientsService()->persistAndFlush($listRecipients);
        }               
        
        return $this->redirect($this->generateUrl('user_view_account_index_index', array(), true));        
    }
    
    
    /**
     * 
     * @param string $listName
     * @param string $email
     * @param boolean $subscribeChoice
     * @return boolean
     */
    private function subscribeChoiceMatchesCurrentSubscription($listName, $email, $subscribeChoice) {
        return $subscribeChoice === $this->getMailchimpService()->listContains($listName, $email);
    }
    
    
    
  
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    private function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    private function getMailchimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    }        
}