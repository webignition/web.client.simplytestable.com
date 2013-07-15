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
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::already on selected plan');
            $this->get('session')->setFlash('plan_subscribe_success', 'already-on-plan');
            return $redirectResponse;
        }        

        try {
            $response = $this->getUserPlanSubscriptionService()->subscribe($this->getUser(), $this->get('request')->request->get('plan'));
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method done');
            
            
            
            if ($response === true) {
                $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method return true');
                $this->get('session')->setFlash('plan_subscribe_success', 'ok');
            } else {
                $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method return ' . $response);
                $this->get('session')->setFlash('plan_subscribe_error', $response);            
            } 
        } catch (\SimplyTestable\WebClientBundle\Exception\UserAccountCardException $userAccountCardException) {
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::stripe card error');
            $this->get('session')->setFlash('user_account_card_exception_message', $userAccountCardException->getMessage());
            $this->get('session')->setFlash('user_account_card_exception_param', $userAccountCardException->getParam());
            $this->get('session')->setFlash('user_account_card_exception_code', $userAccountCardException->getStripeCode());
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