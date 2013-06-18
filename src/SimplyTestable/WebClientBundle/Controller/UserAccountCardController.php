<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountCardController extends AbstractUserAccountController
{   
    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }

        //$plan = $this->getUserService()->getPlanSummary($this->getUser())->getContentObject();
        $card = $this->getUserService()->getCardSummary($this->getUser())->getContentObject();
        
        $currentYear = date('Y');
     
        $viewData = array(            
            'public_site' => $this->container->getParameter('public_site'),              
            'user' => $this->getUser(),
            'card' => $card,
            'stripe_publishable_key' => $this->container->getParameter('stripe_publishable_key'),
            'is_logged_in' => true,
            'expiry_year_start' => $currentYear,
            'expiry_year_end' => $currentYear + 10
        );
     
        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:card.html.twig');       
        return $this->sendResponse($viewData);
    }
    
    
    public function associateAction($stripe_card_token) {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }        

        $this->getUserAccountCardService()->associate($this->getUser(), $stripe_card_token);        
        return $this->redirect($this->generateUrl('user_account_index', null, true));
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserAccountCardService
     */
    protected function getUserAccountCardService() {
        return $this->get('simplytestable.services.useraccountcardservice');
    }     

}