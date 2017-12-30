<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;

use Symfony\Component\HttpFoundation\Cookie;

class IndexController extends CacheableViewController implements IEFiltered {

    const ONE_YEAR_IN_SECONDS = 31536000;

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    public function indexAction() {
        $request = $this->container->get('request');
        $couponService = $this->container->get('simplytestable.services.couponservice');
        $couponService->setRequest($request);

        $hasCoupon = $couponService->has();

        $viewData = [
            'user_create_error' => $this->getFlash('user_create_error'),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation'),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => $this->getRequestedPlan(),
            'redirect' => trim($this->getRequest()->query->get('redirect')),
            'has_coupon' => $hasCoupon
        ];

        $this->getPlansService()->listPremiumOnly();

        if ($hasCoupon) {
            $viewData['coupon'] = $couponService->get();
            $this->getPlansService()->setPriceModifier($viewData['coupon']->getPriceModifier());
        }

        $viewData['plans'] = $this->getPlansService()->getList();

        $response = $this->renderCacheableResponse($viewData);

        $redirect = trim($this->get('request')->query->get('redirect'));
        if ($redirect !== '') {
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
     * @return string
     */
    private function getRequestedPlan() {
        $plan = trim($this->getRequest()->query->get('plan'));

        if (!in_array($plan, ['personal', 'agency', 'business'])) {
            return 'personal';
        }

        return $plan;
    }

    public function getCacheValidatorParameters() {
        $couponService = $this->container->get('simplytestable.services.couponservice');
        $couponService->setRequest($this->getRequest());

        return [
            'user_create_error' => $this->getFlash('user_create_error', false),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation', false),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => $this->getRequestedPlan(),
            'redirect' => trim($this->getRequest()->query->get('redirect')),
            'coupon' => ($couponService->has()) ? $couponService->get()->__toString() : ''
        ];
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\PlansService
     */
    private function getPlansService() {
        return $this->container->get('simplytestable.services.plansService');
    }

}