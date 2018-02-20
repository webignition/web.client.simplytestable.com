<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Model\User\Plan;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Services\PlansService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements IEFiltered
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $couponService = $this->container->get(CouponService::class);
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $flashBagValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\FlashBagValues');
        $templating = $this->container->get('templating');
        $plansService = $this->container->get(PlansService::class);

        $couponService->setRequest($request);
        $plansService->listPremiumOnly();

        $hasCoupon = $couponService->has();
        $redirect = trim($request->query->get('redirect'));

        $userCreateError = $flashBagValuesService->getSingle(UserController::FLASH_BAG_SIGN_UP_ERROR_KEY);
        $userCreateConfirmation = $flashBagValuesService->getSingle('user_create_confirmation');
        $email = strtolower(trim($request->query->get('email')));

        $plans = $plansService->getList();
        $requestedPlan = $this->getRequestedPlan($request->query->get('plan'), $plans);

        $response = $cacheValidatorService->createResponse($request, [
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $email,
            'plan' => $requestedPlan,
            'redirect' => $redirect,
            'coupon' => $hasCoupon ? $couponService->get()->__toString() : ''
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
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
            $coupon = $couponService->get();

            $viewData['coupon'] = $coupon;
            $plansService->setPriceModifier($coupon->getPriceModifier());
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/SignUp/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

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
