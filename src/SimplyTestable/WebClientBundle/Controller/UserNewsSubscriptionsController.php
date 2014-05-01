<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class UserNewsSubscriptionsController extends AbstractUserAccountController
{   
    const ONE_YEAR_IN_SECONDS = 31536000;    
    
    public function updateAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $redirectResponse = $this->redirect($this->generateUrl('user_account_index', array(), true));
        
        $listKeys = array(
            'announcements',
            'updates'
        );
        
        foreach ($listKeys as $listId) {
            $newChoice = filter_var($this->get('request')->request->get($listId), FILTER_VALIDATE_BOOLEAN);
            
            if ($newChoice === true && $this->getMailchimpService()->listContains($listId, $this->getUser()->getUsername()) === true) {
                continue;
            } elseif ($newChoice === false && $this->getMailchimpService()->listContains($listId, $this->getUser()->getUsername()) === false) {
                continue;
            } else {
                if ($newChoice === true) {
                    $this->getMailchimpService()->subscribe($listId, $this->getUser()->getUsername());
                } else {
                    $this->getMailchimpService()->unsubscribe($listId, $this->getUser()->getUsername());
                }
            }
        }
        
        //exit();
        
        //$subscr
        
        //$subscriptionChoices['anno']
        
        //$subscribeToAnnouncements = filter_var($this->get('request')->request->get('announcements'), FILTER_VALIDATE_BOOLEAN);
        //$subscribeToUpdates = filter_var($this->get('request')->request->get('updates'), FILTER_VALIDATE_BOOLEAN);

        
        
//        var_dump();
//        var_dump(filter_var($this->get('request')->request->get('updates'), FILTER_VALIDATE_BOOLEAN));                
        
        return $redirectResponse;        
    }
    
    
    
  
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    private function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }   
}