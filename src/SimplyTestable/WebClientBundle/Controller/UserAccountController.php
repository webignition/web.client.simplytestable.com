<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends AbstractUserAccountController {

    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }

        $plan = $this->getUserService()->getPlanSummary($this->getUser())->getContentObject();
        $card = $this->getUserService()->getCardSummary($this->getUser())->getContentObject();

        $paymentStatus = array();

        if (count($card) === 0) {
            // trialing, active, past_due, canceled, or unpaid
            if ($plan->summary->status != 'trialing') {
                switch ($plan->summary->status) {
                    case 'active':
                        // get latest invoice.created event, get next_payment_attempt from event data
                        $eventData = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.created');
                        break;

                    case 'past_due':
                        // get latest invoice.updated event, get next_payment_attempt from event data
                        $eventData = $this->getUserStripeEventService()->getLatestData($this->getUser(), 'invoice.updated');
                        break;

                    case 'cancelled':
                        // wait and see
                        var_dump("sub cancelled, what do we do?");
                        exit();
                        break;
                }
                
                $nextPaymentDate = new \ExpressiveDate(date('c', $eventData->next_payment_attempt));                
                $paymentStatus['next_payment_attempt'] = $eventData->next_payment_attempt;                
                $paymentStatus['next_payment_attempt_relative'] = $nextPaymentDate->getRelativeDate();                
                $paymentStatus['attempt_count'] = $eventData->attempt_count;                
                
            }
        }

        if (isset($card->exp_month)) {
            $card->exp_month_name = $this->getMonthNameFromNumber($card->exp_month);
        }

        if (isset($plan->summary) && isset($plan->summary->trial_period_days)) {
            $plan->summary->days_of_trial_period = $this->getDayOfTrialPeriod($plan);
        }

        $viewData = array(
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'plan' => $plan,
            'plan_presentation_name' => $this->getPlanPresentationName($plan->name),
            'card' => $card,
            'is_logged_in' => true,
            'user_account_details_update_notice' => $this->getFlash('user_account_details_update_notice'),
            'user_account_details_update_email' => $this->getFlash('user_account_details_update_email'),
            'user_account_details_update_email_confirm_notice' => $this->getFlash('user_account_details_update_email_confirm_notice'),
            'plan_subscribe_error' => $this->getFlash('plan_subscribe_error'),
            'plan_subscribe_success' => $this->getFlash('plan_subscribe_success'),
            'payment_status' => $paymentStatus,
            'stripe' => $this->container->getParameter('stripe')
        );

        if ($this->getUserEmailChangeRequestService()->hasEmailChangeRequest($this->getUser()->getUsername())) {
            $viewData['email_change_request'] = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
            $viewData['token'] = $this->get('request')->query->get('token');
        }

        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:index.html.twig');
        return $this->sendResponse($viewData);
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
     * @param \stdClass $plan
     * @return int
     */
    private function getDayOfTrialPeriod($plan) {
        if (!isset($plan->summary)) {
            return 0;
        }

        return (int) ceil($plan->summary->trial_period_days - ($plan->summary->trial_end - time()) / 86400);
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