<?php

namespace SimplyTestable\WebClientBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends AbstractUserAccountController {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $userSummary = $this->getUserService()->getSummary($this->getUser());
        
        $viewData = array_merge(array(
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'is_logged_in' => true,
            'stripe_event_data' => $this->getUserStripeEvents($userSummary),
            'stripe' => $this->container->getParameter('stripe'),
            'this_url' => $this->generateUrl('user_account_index', array(), true),
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

        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:index.html.twig');
        return $this->sendResponse($viewData);
    }

    
    private function getUserStripeEvents(\SimplyTestable\WebClientBundle\Model\User\Summary $userSummary) {
        if (!$userSummary->hasStripeCustomer()) {
            return array();
        }
        
        if (!$userSummary->getStripeCustomer()->hasSubscription()) {
            return array();
        }
        
        $stripeEvents = array();
        
        //if (!$userSummary->getStripeCustomer()->hasSubscription()) {
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
            
            //return $stripeEvents;
        //}
        
//        if ($userSummary->getStripeCustomer()->getSubscription()->isTrialing() || $userSummary->getStripeCustomer()->getSubscription()->isActive()) {
//            $stripeEvents['invoice'] = $this->getUserStripeEventService()->getLatest($this->getUser(), 'invoice.created');            
//        }
//        
//        ini_set('xdebug.var_display_max_data', 5000);
//        ini_set('xdebug.var_display_max_depth', 10);
//        
//        var_dump($stripeEvents);
//        exit();
//        
//        
//        switch ($userSummary->stripe_customer->subscription->status) {
//            case 'trialing':
//                $stripeEvents['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.created');
//                break;
//            
//            case 'active':
//                $stripeEvents['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.created');
//                break;
//            
//            case 'past_due':
//                $stripeEvents['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.updated');
//                $stripeEvents['charge.failed'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'charge.failed');
//                break;
//            
//            case 'cancelled':
//                var_dump("sub cancelled, what do we do?");
//                exit();                
//                break;
//        }
//            
//        if (isset($stripeEvents['invoice'])) {
//            var_dump($stripeEvents['invoice']->getDataObject()->getObject()->getNextPaymentAttempt());
//            exit();
//            
//            if (!is_null($stripeEvents['invoice']->next_payment_attempt)) {
//                $nextPaymentDate = new \ExpressiveDate(date('c', $stripeEvents['invoice']->next_payment_attempt));
//                $stripeEvents['invoice']->next_payment_attempt_relative = $nextPaymentDate->getRelativeDate();                
//            }
//        }
        
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
        
        return $this->getMonthNameFromNumber($userSummary->getStripeCustomer()->getActiveCard()->getExpiryMonth());                
    }
    

    private function getMonthNameFromNumber($monthNumber) {
        $monthNumber = (int) $monthNumber;

        switch ($monthNumber) {
            case 1:
                return 'January';

            case 2:
                return 'February';

            case 3:
                return 'March';

            case 4:
                return 'April';

            case 5:
                return 'May';

            case 6:
                return 'June';

            case 7:
                return 'July';

            case 8:
                return 'August';

            case 9:
                return 'September';

            case 10:
                return 'October';

            case 11:
                return 'November';

            case 12:
                return 'December';
        }
    }


    /**
     * 
     * @param \stdClass $userSummary
     * @return int
     */
    private function getDayOfTrialPeriod($userSummary) {
        if (!isset($userSummary->stripe_customer)) {
            return 0;
        }
        
        if (!isset($userSummary->stripe_customer->subscription)) {
            return 0;
        }
        
        $trialPeriodRemaining = $userSummary->stripe_customer->subscription->trial_end - time();
        return (int)($userSummary->user_plan->start_trial_period - floor($trialPeriodRemaining / 86400));
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

}