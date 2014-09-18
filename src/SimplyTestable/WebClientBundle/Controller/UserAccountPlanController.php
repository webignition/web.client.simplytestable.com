<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountPlanController extends AbstractUserAccountController
{   
    public function subscribeAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $redirectResponse = $this->redirect($this->generateUrl('view_user_account_plan_index', array(), true) . '#plan-upgrade-downgrade');
        
        $userSummary = $this->getUserService()->getSummary($this->getUser());

        if ($userSummary->hasPlan() && $userSummary->getPlan()->getAccountPlan()->getName() == $this->get('request')->request->get('plan')) {
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::already on selected plan');
            $this->get('session')->getFlashBag()->set('plan_subscribe_success', 'already-on-plan');
            return $redirectResponse;
        }

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $this->getTeamService()->setUser($this->getUser());
            $team = $this->getTeamService()->getTeam();

            if ($team->getLeader() != $this->getUser()->getUsername()) {
                return $redirectResponse;
            }
        }

        try {
            $response = $this->getUserPlanSubscriptionService()->subscribe($this->getUser(), $this->get('request')->request->get('plan'));
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method done');            
            
            if ($response === true) {
                $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method return true');
                $this->get('session')->getFlashBag()->set('plan_subscribe_success', 'ok');
            } else {
                $this->get('logger')->err('UserAccountPlanController::subscribeAction::subscribe method return ' . $response);
                $this->get('session')->getFlashBag()->set('plan_subscribe_error', $response);
            } 
        } catch (\SimplyTestable\WebClientBundle\Exception\UserAccountCardException $userAccountCardException) {
            $this->get('logger')->err('UserAccountPlanController::subscribeAction::stripe card error');
            $this->get('session')->getFlashBag()->set('user_account_card_exception_message', $userAccountCardException->getMessage());
            $this->get('session')->getFlashBag()->set('user_account_card_exception_param', $userAccountCardException->getParam());
            $this->get('session')->getFlashBag()->set('user_account_card_exception_code', $userAccountCardException->getStripeCode());
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


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->container->get('simplytestable.services.teamservice');
    }
}