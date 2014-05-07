<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;

class IndexController extends BaseViewController implements RequiresUser, IEFiltered {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    public function indexAction() {
        $userSummary = $this->getUserService()->getSummary();
        
        $viewData = array_merge(array(
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'stripe_event_data' => $this->getUserStripeEvents($userSummary),
            'stripe' => $this->container->getParameter('stripe'),
            'this_url' => $this->generateUrl('view_user_account_index_index', array(), true),
            'premium_plan_launch_offer_end' => $this->container->getParameter('premium_plan_launch_offer_end'),
            'plans' => $this->container->getParameter('plans'),
            'mailchimp_updates_subscribed' => $this->getMailchimpService()->listContains('updates', $this->getUser()->getUsername()),
            'mailchimp_announcements_subscribed' => $this->getMailchimpService()->listContains('announcements', $this->getUser()->getUsername()),
            'card_expiry_month' => $this->getCardExpiryMonth($userSummary)
        ), $this->getViewFlashValues(array(
            'user_account_details_update_notice',
            'user_account_details_update_email',
            'user_account_details_update_email_confirm_notice',
            'plan_subscribe_error',
            'plan_subscribe_success',
            'user_account_card_exception_message',
            'user_account_card_exception_param',
            'user_account_card_exception_code'
        )));

        if ($this->getUserEmailChangeRequestService()->hasEmailChangeRequest($this->getUser()->getUsername())) {
            $viewData['email_change_request'] = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
            $viewData['token'] = $this->get('request')->query->get('token');
        }

        return $this->renderResponse($viewData);
    }

    
    private function getUserStripeEvents(\SimplyTestable\WebClientBundle\Model\User\Summary $userSummary) {
        if (!$userSummary->hasStripeCustomer()) {
            return array();
        }
        
        if (!$userSummary->getStripeCustomer()->hasSubscription()) {
            return array();
        }
        
        $stripeEvents = array();       

        $eventKeys = array(
            'invoice.updated',
            'invoice.created'
        );

        foreach ($eventKeys as $eventKey) {
            $eventData = $this->getUserStripeEventService()->getLatest($this->getUser(), $eventKey);
            if (!is_null($eventData) && !isset($stripeEvents['invoice'])) {
                $stripeEvents['invoice'] = $eventData->getDataObject()->getObject();
            }
        }

        
        return $stripeEvents;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User\Summary $userSummary
     * @return string|null
     */
    private function getCardExpiryMonth(\SimplyTestable\WebClientBundle\Model\User\Summary $userSummary) {
        if (!$userSummary->hasStripeCustomer()) {
            return null;
        }
        
        if (!$userSummary->getStripeCustomer()->hasActiveCard()) {
            return null;
        }
        
        return \DateTime::createFromFormat('!m', $userSummary->getStripeCustomer()->getActiveCard()->getExpiryMonth())->format('F');               
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
     * @return \SimplyTestable\WebClientBundle\Services\UserStripeEventService
     */
    private function getUserStripeEventService() {
        return $this->container->get('simplytestable.services.userstripeeventservice');
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
     * @return \SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService
     */
    protected function getUserEmailChangeRequestService() {
        return $this->get('simplytestable.services.useremailchangerequestservice');
    }

}