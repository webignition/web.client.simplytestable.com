<?php

namespace App\Controller\View\User\Account;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CurrencyMap;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\PlansService;
use App\Services\TeamService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class PlanController extends AbstractUserAccountController
{
    /**
     * @var PlansService
     */
    private $plansService;

    /**
     * @var CurrencyMap
     */
    private $currencyMap;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UserService $userService,
        UserManager $userManager,
        TeamService $teamService,
        FlashBagValues $flashBagValues,
        PlansService $plansService,
        CurrencyMap $currencyMap
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheableResponseFactory,
            $userService,
            $userManager,
            $teamService,
            $flashBagValues
        );

        $this->plansService = $plansService;
        $this->currencyMap = $currencyMap;
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        $userSummary = $this->userService->getSummary();
        $team = null;

        if ($userSummary->getPlan()->getAccountPlan()->getIsCustom()) {
            return new RedirectResponse($this->generateUrl('view_user_account'));
        }

        $stripeCustomer = $userSummary->getStripeCustomer();

        if (!empty($stripeCustomer) && $stripeCustomer->hasDiscount()) {
            $priceModifier = (100 - $stripeCustomer->getDiscount()->getCoupon()->getPercentOff()) / 100;
            $this->plansService->setPriceModifier($priceModifier);
        }

        $viewData = array_merge(
            [
                'user_summary' => $userSummary,
                'plan_presentation_name' => ucwords($userSummary->getPlan()->getAccountPlan()->getName()),
                'plans' => $this->plansService->listPremiumOnly()->getList(),
                'currency_map' => $this->currencyMap->getCurrencyMap(),
            ],
            $this->flashBagValues->get([
                'plan_subscribe_error',
                'plan_subscribe_success',
            ])
        );

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $viewData['team'] = $this->teamService->getTeam();
        }

        return $this->renderWithDefaultViewParameters('user-account-plan.html.twig', $viewData);
    }
}
