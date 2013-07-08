<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountPlanController extends AbstractUserAccountController
{   
    public function subscribeAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $redirectResponse = $this->redirect($this->generateUrl('user_account_index', array(), true));
        
        $userSummary = $this->getUserService()->getSummary($this->getUser())->getContentObject();
        if (isset($userSummary->user_plan) && $userSummary->user_plan->plan->name == $this->get('request')->request->get('plan')) {
            $this->get('session')->setFlash('plan_subscribe_success', 'already-on-plan');
        } else {
            $response = $this->getUserPlanSubscriptionService()->subscribe($this->getUser(), $this->get('request')->request->get('plan'));
            if ($response === true) {
                $this->get('session')->setFlash('plan_subscribe_success', 'ok');
            } else {
                $this->get('session')->setFlash('plan_subscribe_error', $response);            
            }            
        }
                
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