<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserStripeEventService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use Symfony\Component\Routing\RouterInterface;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered
{
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request)
    {
        $userService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserService');
        $mailChimpListRecipientsService = $this->container->get('SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService');
        $teamService = $this->container->get('SimplyTestable\WebClientBundle\Services\TeamService');
        $emailChangeRequestService = $this->get('SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService');
        $templating = $this->container->get('templating');
        $userStripeEventService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserStripeEventService');
        $flashBagValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\FlashBagValues');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();
        $username = $user->getUsername();
        $userSummary = $userService->getSummary();

        $updatesListRecipients = $mailChimpListRecipientsService->get('updates');
        $announcementsListRecipients = $mailChimpListRecipientsService->get('announcements');

        $viewData = array_merge(
            $this->getDefaultViewParameters(),
            [
                'user_summary' => $userSummary,
                'plan_presentation_name' => $this->getPlanPresentationName(
                    $userSummary->getPlan()->getAccountPlan()->getName()
                ),
                'stripe_event_data' => $this->getUserStripeEvents($user, $userSummary, $userStripeEventService),
                'mailchimp_updates_subscribed' => $updatesListRecipients->contains($username),
                'mailchimp_announcements_subscribed' => $announcementsListRecipients->contains($username),
                'card_expiry_month' => $this->getCardExpiryMonth($userSummary),
                'currency_map' => $this->container->getParameter('currency_map')
            ],
            $flashBagValuesService->get([
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
            ])
        );

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $viewData['team'] = $teamService->getTeam();
        }

        $emailChangeRequest = $emailChangeRequestService->getEmailChangeRequest($user->getUsername());

        if (!empty($emailChangeRequest)) {
            $viewData['email_change_request'] = $emailChangeRequest;
            $viewData['token'] = $request->query->get('token');
        }

        return new Response(
            $templating->render(
                'SimplyTestableWebClientBundle:bs3/User/Account/Index:index.html.twig',
                $viewData
            )
        );
    }

    /**
     * @param User $user
     * @param UserSummary $userSummary
     * @param UserStripeEventService $userStripeEventService
     *
     * @return array
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function getUserStripeEvents(
        User $user,
        UserSummary $userSummary,
        UserStripeEventService $userStripeEventService
    ) {
        $stripeCustomer = $userSummary->getStripeCustomer();

        if (empty($stripeCustomer)) {
            return [];
        }

        if (!$stripeCustomer->hasSubscription()) {
            return [];
        }

        $stripeEvents = [];

        $eventKeys = [
            'invoice.updated',
            'invoice.created'
        ];

        foreach ($eventKeys as $eventKey) {
            $eventData = $userStripeEventService->getLatest($user, $eventKey);
            if (!is_null($eventData) && !isset($stripeEvents['invoice'])) {
                $stripeEvents['invoice'] = $eventData->getDataObject()->getObject();
            }
        }

        return $stripeEvents;
    }

    /**
     * @param UserSummary $userSummary
     *
     * @return string|null
     */
    private function getCardExpiryMonth(UserSummary $userSummary)
    {
        $stripeCustomer = $userSummary->getStripeCustomer();

        if (empty($stripeCustomer)) {
            return null;
        }

        if (!$stripeCustomer->hasActiveCard()) {
            return null;
        }

        return \DateTime::createFromFormat(
            '!m',
            $stripeCustomer->getActiveCard()->getExpiryMonth()
        )->format('F');
    }

    /**
     * @param string $plan
     *
     * @return string
     */
    private function getPlanPresentationName($plan)
    {
        if (substr_count($plan, '-custom')) {
            $planParts = explode('-custom', $plan);
            return $planParts[0];
        }

        return ucwords($plan);
    }
}
