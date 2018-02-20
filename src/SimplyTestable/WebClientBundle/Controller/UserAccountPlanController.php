<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UserAccountPlanController extends Controller implements RequiresPrivateUser
{
    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function subscribeAction(Request $request)
    {
        $router = $this->container->get('router');
        $userService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserService');
        $session = $this->container->get('session');
        $teamService = $this->container->get('simplytestable.services.teamservice');
        $userAccountPlanSubscriptionService = $this->get('simplytestable.services.userplansubscriptionservice');
        $logger = $this->container->get('logger');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();
        $redirectResponse = new RedirectResponse($router->generate(
            'view_user_account_plan_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $userSummary = $userService->getSummary($user);
        $requestData = $request->request;
        $flashBag = $session->getFlashBag();

        $plan = $requestData->get('plan');

        if ($userSummary->hasPlan() && $userSummary->getPlan()->getAccountPlan()->getName() === $plan) {
            $flashBag->set('plan_subscribe_success', 'already-on-plan');

            return $redirectResponse;
        }

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $team = $teamService->getTeam();

            if ($team->getLeader() != $user->getUsername()) {
                return $redirectResponse;
            }
        }

        try {
            $userAccountPlanSubscriptionService->subscribe($user, $plan);
            $flashBag->set('plan_subscribe_success', 'ok');
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $logger->error(sprintf(
                'UserAccountPlanController::subscribeAction::subscribe method return %s ',
                $coreApplicationRequestException->getCode()
            ));

            $flashBag->set('plan_subscribe_error', $coreApplicationRequestException->getCode());
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set('plan_subscribe_error', 503);
        } catch (UserAccountCardException $userAccountCardException) {
            $logger->error('UserAccountPlanController::subscribeAction::stripe card error');

            $flashBag->set('user_account_card_exception_message', $userAccountCardException->getMessage());
            $flashBag->set('user_account_card_exception_param', $userAccountCardException->getParam());
            $flashBag->set('user_account_card_exception_code', $userAccountCardException->getStripeCode());
        }

        return $redirectResponse;
    }
}
