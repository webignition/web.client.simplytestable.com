<?php

namespace App\Controller\View\User\Account;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheValidatorService;
use App\Services\Configuration\CurrencyMap;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\MailChimp\ListRecipientsService as MailChimpListRecipientsService;
use App\Services\TeamService;
use App\Services\UserEmailChangeRequestService;
use App\Services\UserManager;
use App\Services\UserService;
use App\Services\UserStripeEventService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\User\Summary as UserSummary;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class AccountController extends AbstractUserAccountController
{
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    /**
     * @var MailChimpListRecipientsService
     */
    private $mailChimpListRecipientsService;

    /**
     * @var UserEmailChangeRequestService
     */
    private $emailChangeRequestService;

    /**
     * @var UserStripeEventService
     */
    private $userStripeEventService;

    /**
     * @var CurrencyMap
     */
    private $currencyMap;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UserService $userService
     * @param UserManager $userManager
     * @param TeamService $teamService
     * @param FlashBagValues $flashBagValues
     * @param MailChimpListRecipientsService $mailChimpListRecipientsService
     * @param UserEmailChangeRequestService $userEmailChangeRequestService
     * @param UserStripeEventService $userStripeEventService
     * @param CurrencyMap $currencyMap
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UserService $userService,
        UserManager $userManager,
        TeamService $teamService,
        FlashBagValues $flashBagValues,
        MailChimpListRecipientsService $mailChimpListRecipientsService,
        UserEmailChangeRequestService $userEmailChangeRequestService,
        UserStripeEventService $userStripeEventService,
        CurrencyMap $currencyMap
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $userService,
            $userManager,
            $teamService,
            $flashBagValues
        );

        $this->mailChimpListRecipientsService = $mailChimpListRecipientsService;
        $this->emailChangeRequestService = $userEmailChangeRequestService;
        $this->userStripeEventService = $userStripeEventService;
        $this->currencyMap = $currencyMap;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserSignInRedirectResponseRoute()
    {
        return 'view_user_account';
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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();
        $username = $user->getUsername();
        $userSummary = $this->userService->getSummary();

        $updatesListRecipients = $this->mailChimpListRecipientsService->get('updates');
        $announcementsListRecipients = $this->mailChimpListRecipientsService->get('announcements');

        $viewData = array_merge(
            [
                'user_summary' => $userSummary,
                'plan_presentation_name' => $this->getPlanPresentationName(
                    $userSummary->getPlan()->getAccountPlan()->getName()
                ),
                'stripe_event_data' => $this->getUserStripeEvents($user, $userSummary),
                'mailchimp_updates_subscribed' => $updatesListRecipients->contains($username),
                'mailchimp_announcements_subscribed' => $announcementsListRecipients->contains($username),
                'card_expiry_month' => $this->getCardExpiryMonth($userSummary),
                'currency_map' => $this->currencyMap->getCurrencyMap(),
            ],
            $this->flashBagValues->get([
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
            $viewData['team'] = $this->teamService->getTeam();
        }

        $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($user->getUsername());

        if (!empty($emailChangeRequest)) {
            $viewData['email_change_request'] = $emailChangeRequest;
            $viewData['token'] = $request->query->get('token');
        }

        return $this->renderWithDefaultViewParameters('user-account.html.twig', $viewData);
    }

    /**
     * @param User $user
     * @param UserSummary $userSummary
     *
     * @return array
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function getUserStripeEvents(User $user, UserSummary $userSummary)
    {
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
            $eventData = $this->userStripeEventService->getLatest($user, $eventKey);
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
