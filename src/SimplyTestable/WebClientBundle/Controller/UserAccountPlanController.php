<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountPlanController extends AbstractUserAccountController
{   
    public function subscribeAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $response = $this->getUserPlanSubscriptionService()->subscribe($this->getUser(), $this->get('request')->request->get('plan'));
        if ($response === true) {
            $this->get('session')->setFlash('plan_subscribe_success', 'ok');
        } else {
            $this->get('session')->setFlash('plan_subscribe_error', $response);            
        }
        
        $redirectResponse = $this->redirect($this->generateUrl('user_account_index', array(), true));        
        return $redirectResponse;
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserPlanSubscriptionService
     */
    protected function getUserPlanSubscriptionService() {
        return $this->get('simplytestable.services.userplansubscriptionservice');
    }
}