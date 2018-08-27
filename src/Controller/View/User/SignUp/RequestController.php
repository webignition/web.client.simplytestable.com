<?php

namespace App\Controller\View\User\SignUp;

use App\Controller\Action\User\UserController;
use App\Controller\View\User\AbstractUserController;
use App\Model\User\Plan;
use App\Request\User\SignUpRequest;
use App\Services\CacheableResponseFactory;
use App\Services\CouponService;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\PlansService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RequestController extends AbstractUserController
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

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        FlashBagValues $flashBagValues,
        CouponService $couponService,
        PlansService $plansService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory, $flashBagValues);

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

        $requestData = $request->query;
        $email = strtolower(trim($requestData->get('email')));
        $redirect = trim($requestData->get('redirect'));
        $errorField = $this->flashBagValues->getSingle('user_signup_error_field');
        $errorState = $this->flashBagValues->getSingle('user_signup_error_state');
        $userCreateError = $this->flashBagValues->getSingle(UserController::FLASH_SIGN_UP_ERROR_KEY);
        $userCreateConfirmation = $this->flashBagValues->getSingle('user_create_confirmation');

        $coupon = $this->couponService->get();
        if ($coupon) {
            $this->plansService->setPriceModifier($coupon->getPriceModifier());
        }

        $plans = $this->plansService->getList();
        $requestedPlan = $this->getRequestedPlan($requestData->get('plan'), $plans);
        $selectedField = empty($email) ? SignUpRequest::PARAMETER_EMAIL : $errorField;

        $viewData = [
            'error_field' => $errorField,
            'error_state' => $errorState,
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $email,
            'plan' => $requestedPlan,
            'redirect' => $redirect,
            'coupon' => $coupon,
            'selected_field' => $selectedField,
        ];

        $response = $this->cacheableResponseFactory->createResponse($request, $viewData);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $viewData['plans'] = $plans;

        $response = $this->renderWithDefaultViewParameters('user-sign-up.html.twig', $viewData, $response);

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
