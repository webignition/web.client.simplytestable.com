<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered {

    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], true));
    }

    public function indexAction() {
        $userSummary = $this->getUserService()->getSummary();

        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
        $updatesListRecipients = $mailChimpListRecipientsService->get('updates');
        $announcementsListRecipients = $mailChimpListRecipientsService->get('announcements');

        $user = $this->getUser();
        $username = $user->getUsername();

        $viewData = array_merge(array(
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName(
                $userSummary->getPlan()->getAccountPlan()->getName()
            ),
            'stripe_event_data' => $this->getUserStripeEvents($userSummary),
            'stripe' => $this->container->getParameter('stripe'),
            'this_url' => $this->generateUrl('view_user_account_index_index', array(), true),
            'premium_plan_launch_offer_end' => $this->container->getParameter('premium_plan_launch_offer_end'),
            'mailchimp_updates_subscribed' => $updatesListRecipients->contains($username),
            'mailchimp_announcements_subscribed' => $announcementsListRecipients->contains($username),
            'card_expiry_month' => $this->getCardExpiryMonth($userSummary),
            'currency_map' => $this->container->getParameter('currency_map')
        ), $this->getViewFlashValues(array(
            'user_account_details_update_notice',
            'user_account_details_update_email',
            'user_account_details_update_email_confirm_notice',
            'user_account_card_exception_message',
            'user_account_card_exception_param',
            'user_account_card_exception_code',
            'user_account_details_resend_email_change_notice',
            'user_account_details_resend_email_change_error',
            'user_account_details_update_email_request_notice',
            'user_account_details_update_password_request_notice',
            'user_account_newssubscriptions_update'
        )));

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $this->getTeamService()->setUser($this->getUser());
            $viewData['team'] = $this->getTeamService()->getTeam();
        }

        if ($this->getUserEmailChangeRequestService()->hasEmailChangeRequest($this->getUser()->getUsername())) {
            $viewData['email_change_request'] = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($username);
            $viewData['token'] = $this->get('request')->query->get('token');
        }

        return $this->renderResponse($this->getRequest(), $viewData);
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
        if (substr_count($plan, '-custom')) {
            $planParts = explode('-custom', $plan);
            return $planParts[0];
        }

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


    protected function getAllowedContentTypes() {
        return array_merge(['application/json'], parent::getAllowedContentTypes());
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->container->get('simplytestable.services.teamservice');
    }

}