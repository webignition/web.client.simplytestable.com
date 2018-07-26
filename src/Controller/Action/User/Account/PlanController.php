<?php

namespace App\Controller\Action\User\Account;

use Psr\Log\LoggerInterface;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAccountCardException;
use App\Services\TeamService;
use App\Services\UserManager;
use App\Services\UserPlanSubscriptionService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class PlanController extends AbstractUserAccountController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * @var UserPlanSubscriptionService
     */
    private $userPlanSubscriptionService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        UserService $userService,
        TeamService $teamService,
        UserPlanSubscriptionService $userPlanSubscriptionService,
        LoggerInterface $logger
    ) {
        parent::__construct($router, $userManager, $flashBag);

        $this->userService = $userService;
        $this->teamService = $teamService;
        $this->userPlanSubscriptionService = $userPlanSubscriptionService;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function subscribeAction(Request $request)
    {
        $user = $this->userManager->getUser();
        $redirectResponse = new RedirectResponse($this->generateUrl('view_user_account_plan'));

        $userSummary = $this->userService->getSummary($user);
        $requestData = $request->request;

        $plan = $requestData->get('plan');

        if ($userSummary->hasPlan() && $userSummary->getPlan()->getAccountPlan()->getName() === $plan) {
            $this->flashBag->set('plan_subscribe_success', 'already-on-plan');

            return $redirectResponse;
        }

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $team = $this->teamService->getTeam();

            if ($team->getLeader() != $user->getUsername()) {
                return $redirectResponse;
            }
        }

        try {
            $this->userPlanSubscriptionService->subscribe($user, $plan);
            $this->flashBag->set('plan_subscribe_success', 'ok');
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $this->logger->error(sprintf(
                'UserAccountPlanController::subscribeAction::subscribe method return %s ',
                $coreApplicationRequestException->getCode()
            ));

            $this->flashBag->set('plan_subscribe_error', $coreApplicationRequestException->getCode());
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $this->flashBag->set('plan_subscribe_error', 503);
        } catch (UserAccountCardException $userAccountCardException) {
            $this->logger->error('UserAccountPlanController::subscribeAction::stripe card error');

            $this->flashBag->set('user_account_card_exception_message', $userAccountCardException->getMessage());
            $this->flashBag->set('user_account_card_exception_param', $userAccountCardException->getParam());
            $this->flashBag->set('user_account_card_exception_code', $userAccountCardException->getStripeCode());
        }

        return $redirectResponse;
    }
}
