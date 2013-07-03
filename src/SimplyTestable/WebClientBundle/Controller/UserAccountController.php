<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends AbstractUserAccountController {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $userSummary = $this->getUserService()->getSummary($this->getUser())->getContentObject();
        
        //$this->getUserStripeEventData($userSummary);
//        
//        var_dump($userSummary, $userSummary->user_plan->plan->name);
//        exit();

        //$paymentStatus = array();
//        $stripeEventData = array();
//
//
//
//
//
//        if (isset($card->exp_month)) {
//            $card->exp_month_name = $this->getMonthNameFromNumber($card->exp_month);
//        }
//
//        if (isset($plan->summary) && isset($plan->summary->trial_period_days)) {
//            $plan->summary->days_of_trial_period = $this->getDayOfTrialPeriod($plan);
//        }
        
        if (isset($userSummary->stripe_customer)) {
            $userSummary->stripe_customer->subscription->day_of_trial_period = $this->getDayOfTrialPeriod($userSummary);
        } 
        
        if (isset($userSummary->stripe_customer->active_card)) {
            $userSummary->stripe_customer->active_card->exp_month_name = $this->getMonthNameFromNumber($userSummary->stripe_customer->active_card->exp_month);
            $cardCheckFailures = array();
            
            foreach ($userSummary->stripe_customer->active_card as $cardKey => $cardValue) {                
                if ($this->isCardKeyCheckFailure($cardKey, $cardValue)) {
                    $cardCheckFailures[] = $this->getCardFieldFromCheckKey($cardKey);
                }
            }
            
            if (count($cardCheckFailures)) {
                $userSummary->stripe_customer->active_card->check_failures = $cardCheckFailures;
            }
        }

        $viewData = array(
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->user_plan->plan->name),
            'is_logged_in' => true,
            'user_account_details_update_notice' => $this->getFlash('user_account_details_update_notice'),
            'user_account_details_update_email' => $this->getFlash('user_account_details_update_email'),
            'user_account_details_update_email_confirm_notice' => $this->getFlash('user_account_details_update_email_confirm_notice'),
            'plan_subscribe_error' => $this->getFlash('plan_subscribe_error'),
            'plan_subscribe_success' => $this->getFlash('plan_subscribe_success'),
            'stripe_event_data' => $this->getUserStripeEventData($userSummary),
            'stripe' => $this->container->getParameter('stripe'),
            'this_url' => $this->generateUrl('user_account_index', array(), true)
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
     * @param string $checkKey
     * @return string
     */
    private function getCardFieldFromCheckKey($checkKey) {
        if (!$this->isCardKeyCheckKey($checkKey)) {
            return $checkKey;
        }
        
        return substr($checkKey, 0, strlen($checkKey) - strlen(self::STRIPE_CARD_CHECK_KEY_POSTFIX));
    }
    
    
    /**
     * 
     * @param string $cardKey
     * @param mixed $cardValue
     * @return boolean
     */
    private function isCardKeyCheckFailure($cardKey, $cardValue) {        
        return $this->isCardKeyCheckKey($cardKey) && $cardValue === 'fail';
    }
    
    
    /**
     * 
     * @param string $cardKey
     * @return boolean
     */
    private function isCardKeyCheckKey($cardKey) {
        return substr($cardKey, strlen($cardKey) - strlen(self::STRIPE_CARD_CHECK_KEY_POSTFIX)) == self::STRIPE_CARD_CHECK_KEY_POSTFIX;                
    }
    
    private function getUserStripeEventData($userSummary) {
        if (!isset($userSummary->stripe_customer)) {
            return array();
        }
        
        $stripeEventData = array();
        
        switch ($userSummary->stripe_customer->subscription->status) {
            case 'trialing':
                $stripeEventData['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.created');
                break;
            
            case 'active':
                $stripeEventData['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.created');
                break;
            
            case 'past_due':
                $stripeEventData['invoice'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.updated');
                $stripeEventData['charge.failed'] = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'charge.failed');
                break;
            
            case 'cancelled':
                var_dump("sub cancelled, what do we do?");
                exit();                
                break;
        }
            
        if (isset($stripeEventData['invoice.updated'])) {
            $nextPaymentDate = new \ExpressiveDate(date('c', $stripeEventData['invoice.updated']->next_payment_attempt));
            $stripeEventData['invoice.updated']->next_payment_attempt_relative = $nextPaymentDate->getRelativeDate();
        }
        
        return $stripeEventData;
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
        
        return (int) ceil($userSummary->stripe_customer->subscription->plan->trial_period_days - ($userSummary->stripe_customer->subscription->trial_end - time()) / 86400);
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

}