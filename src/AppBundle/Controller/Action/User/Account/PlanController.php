<?php

namespace AppBundle\Controller\Action\User\Account;

use Psr\Log\LoggerInterface;
use AppBundle\Exception\CoreApplicationReadOnlyException;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Exception\UserAccountCardException;
use AppBundle\Services\TeamService;
use AppBundle\Services\UserManager;
use AppBundle\Services\UserPlanSubscriptionService;
use AppBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    /**
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param UserService $userService
     * @param TeamService $teamService
     * @param UserPlanSubscriptionService $userPlanSubscriptionService
     * @param LoggerInterface $logger
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        UserService $userService,
        TeamService $teamService,
        UserPlanSubscriptionService $userPlanSubscriptionService,
        LoggerInterface $logger
    ) {
        parent::__construct($router, $userManager, $session);

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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();
        $redirectResponse = new RedirectResponse($this->generateUrl('view_user_account_plan_index'));

        $userSummary = $this->userService->getSummary($user);
        $requestData = $request->request;
        $flashBag = $this->session->getFlashBag();

        $plan = $requestData->get('plan');

        if ($userSummary->hasPlan() && $userSummary->getPlan()->getAccountPlan()->getName() === $plan) {
            $flashBag->set('plan_subscribe_success', 'already-on-plan');

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
            $flashBag->set('plan_subscribe_success', 'ok');
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $this->logger->error(sprintf(
                'UserAccountPlanController::subscribeAction::subscribe method return %s ',
                $coreApplicationRequestException->getCode()
            ));

            $flashBag->set('plan_subscribe_error', $coreApplicationRequestException->getCode());
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set('plan_subscribe_error', 503);
        } catch (UserAccountCardException $userAccountCardException) {
            $this->logger->error('UserAccountPlanController::subscribeAction::stripe card error');

            $flashBag->set('user_account_card_exception_message', $userAccountCardException->getMessage());
            $flashBag->set('user_account_card_exception_param', $userAccountCardException->getParam());
            $flashBag->set('user_account_card_exception_code', $userAccountCardException->getStripeCode());
        }

        return $redirectResponse;
    }
}
