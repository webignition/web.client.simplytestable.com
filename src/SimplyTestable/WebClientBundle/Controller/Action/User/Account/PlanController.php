<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Services\TeamService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserPlanSubscriptionService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @param UserService $userService
     * @param TeamService $teamService
     * @param UserPlanSubscriptionService $userPlanSubscriptionService
     * @param LoggerInterface $logger
     * @param UserManager $userManager
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        UserService $userService,
        TeamService $teamService,
        UserPlanSubscriptionService $userPlanSubscriptionService,
        LoggerInterface $logger,
        UserManager $userManager,
        RouterInterface $router,
        SessionInterface $session
    ) {
        parent::__construct($userManager, $router, $session);

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
        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_plan_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

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
