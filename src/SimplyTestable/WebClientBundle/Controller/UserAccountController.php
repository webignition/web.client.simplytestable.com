<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends AbstractUserAccountController
{   
    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }        

        $plan = $this->getUserService()->getPlanSummary($this->getUser())->getContentObject();
        
        if (isset($plan->summary) && isset($plan->summary->trial_period_days)) {
            $plan->summary->days_of_trial_period = $this->getDayOfTrialPeriod($plan);
        }
      
        $viewData = array(            
            'public_site' => $this->container->getParameter('public_site'),              
            'user' => $this->getUser(),
            'plan' => $plan,
            'plan_presentation_name' => $this->getPlanPresentationName($plan->name),
            'is_logged_in' => true,
            'user_account_details_update_notice' => $this->getFlash('user_account_details_update_notice'),
            'user_account_details_update_email' => $this->getFlash('user_account_details_update_email'),
            'user_account_details_update_email_confirm_notice' => $this->getFlash('user_account_details_update_email_confirm_notice'),
            'plan_subscribe_error' => $this->getFlash('plan_subscribe_error'),
            'plan_subscribe_success' => $this->getFlash('plan_subscribe_success'),
        );
        
        if ($this->getUserEmailChangeRequestService()->hasEmailChangeRequest($this->getUser()->getUsername())) {
            $viewData['email_change_request'] = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
            $viewData['token'] = $this->get('request')->query->get('token');
        }
        
        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:index.html.twig');       
        return $this->sendResponse($viewData);
    }
    
    
    /**
     * 
     * @param \stdClass $plan
     * @return int
     */
    private function getDayOfTrialPeriod($plan) {
        if (!isset($plan->summary)) {
            return 0;
        }
        
        return (int)ceil($plan->summary->trial_period_days - ($plan->summary->trial_end - time()) / 86400);
    }
    
    
    /**
     * 
     * @param string $plan
     * @return string
     */
    private function getPlanPresentationName($plan) {
        return ucwords($plan);
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService
     */
    protected function getUserEmailChangeRequestService() {
        return $this->get('simplytestable.services.useremailchangerequestservice');
    }
}