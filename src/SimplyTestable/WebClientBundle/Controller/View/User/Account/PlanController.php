<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PlanController extends BaseViewController implements RequiresPrivateUser, IEFiltered
{
    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(Request $request)
    {
        $router = $this->container->get('router');

        $redirectUrl = $router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode(json_encode(['route' => 'view_user_account_plan_index']))
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws WebResourceException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $teamService = $this->container->get('simplytestable.services.teamservice');
        $templating = $this->container->get('templating');
        $planService = $this->container->get('simplytestable.services.plansservice');
        $router = $this->container->get('router');
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');

        $user = $userService->getUser();
        $userSummary = $userService->getSummary();
        $team = null;

        if ($userSummary->getPlan()->getAccountPlan()->getIsCustom()) {
            $redirectUrl = $router->generate(
                'view_user_account_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $stripeCustomer = $userSummary->getStripeCustomer();

        if (!empty($stripeCustomer) && $stripeCustomer->hasDiscount()) {
            $priceModifier = (100 - $stripeCustomer->getDiscount()->getCoupon()->getPercentOff()) / 100;
            $planService->setPriceModifier($priceModifier);
        }

        $viewData = array_merge(
            $this->getDefaultViewParameters(),
            [
                'user_summary' => $userSummary,
                'plan_presentation_name' => ucwords($userSummary->getPlan()->getAccountPlan()->getName()),
                'plans' => $planService->listPremiumOnly()->getList(),
                'currency_map' => $this->container->getParameter('currency_map'),
            ],
            $flashBagValuesService->get([
                'plan_subscribe_error',
                'plan_subscribe_success',
            ])
        );

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $viewData['team'] = $teamService->getTeam();
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/Account/Plan:index.html.twig',
            $viewData
        );

        return new Response($content);
    }
}
