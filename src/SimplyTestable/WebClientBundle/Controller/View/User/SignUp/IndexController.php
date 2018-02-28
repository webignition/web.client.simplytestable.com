<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Controller\View\User\AbstractUserController;
use SimplyTestable\WebClientBundle\Model\User\Plan;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\PlansService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends AbstractUserController
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var PlansService
     */
    private $plansService;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     * @param CouponService $couponService
     * @param PlansService $plansService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues,
        CouponService $couponService,
        PlansService $plansService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

        $this->couponService = $couponService;
        $this->plansService = $plansService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->couponService->setRequest($request);
        $this->plansService->listPremiumOnly();

        $hasCoupon = $this->couponService->has();
        $redirect = trim($request->query->get('redirect'));

        $userCreateError = $this->flashBagValues->getSingle(UserController::FLASH_BAG_SIGN_UP_ERROR_KEY);
        $userCreateConfirmation = $this->flashBagValues->getSingle('user_create_confirmation');
        $email = strtolower(trim($request->query->get('email')));

        $plans = $this->plansService->getList();
        $requestedPlan = $this->getRequestedPlan($request->query->get('plan'), $plans);

        $response = $this->cacheValidator->createResponse($request, [
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $email,
            'plan' => $requestedPlan,
            'redirect' => $redirect,
            'coupon' => $hasCoupon ? $this->couponService->get()->__toString() : ''
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'email' => $email,
            'plan' => $requestedPlan,
            'redirect' => $redirect,
            'has_coupon' => $hasCoupon,
            'plans' => $plans,
        ];

        if ($userCreateError) {
            $viewData['user_create_error'] = $userCreateError;
        }

        if ($userCreateConfirmation) {
            $viewData['user_create_confirmation'] = $userCreateConfirmation;
        }

        if ($hasCoupon) {
            $coupon = $this->couponService->get();

            $viewData['coupon'] = $coupon;
            $this->plansService->setPriceModifier($coupon->getPriceModifier());
        }

        $response = $this->renderWithDefaultViewParameters(
            'SimplyTestableWebClientBundle:bs3/User/SignUp/Index:index.html.twig',
            $viewData,
            $response
        );

        if (!empty($redirect)) {
            $cookie = new Cookie(
                'simplytestable-redirect',
                $redirect,
                time() + self::ONE_YEAR_IN_SECONDS,
                '/',
                '.simplytestable.com',
                false,
                true
            );

            $response->headers->setCookie($cookie);
        }

        return $response;
    }


    /**
     * @param string $plan
     * @param Plan[] $plans
     *
     * @return string
     */
    private function getRequestedPlan($plan, array $plans)
    {
        $planIdentifiers = array_keys($plans);
        array_walk($planIdentifiers, function (&$planIdentifier) {
            $planIdentifier = strtolower($planIdentifier);
        });

        if (!in_array($plan, $planIdentifiers)) {
            $plan = 'personal';
        }

        return $plan;
    }
}
