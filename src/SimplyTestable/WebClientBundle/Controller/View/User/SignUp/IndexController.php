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

//        ini_set('xdebug.var_display_max_depth', 5);
//        $plans = $this->getPlansService()->listPremiumOnly()->getList();
//
//        var_dump($plans);
//        exit();


        $viewData = [
            'user_create_error' => $this->getFlash('user_create_error'),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation'),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => $this->getRequestedPlan(),
            'redirect' => trim($this->getRequest()->query->get('redirect')),
            'has_discount_code' => $this->hasDiscountCode()
        ];

        $this->getPlansService()->listPremiumOnly();

        if ($this->hasDiscountCode()) {
            $discountCode = $this->getDiscountCodeDataFromCookie();



            $priceModifier = 1 - ($discountCode['percent_off'] / 100);





            //var_dump($discountCode['percent_off'], $priceModifier);

            $viewData['discount_code'] = $discountCode;


            $this->getPlansService()->setPriceModifier($priceModifier);
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


    /**
     * @return bool
     */
    private function hasDiscountCode() {
        $discountCodeData = $this->getDiscountCodeDataFromCookie();
        return !empty($discountCodeData);
    }


    private function getDiscountCodeDataFromCookie() {
        if (!$this->getRequest()->cookies->has('simplytestable-signup-code')) {
            return [];
        }

        $this->getDiscountCodeService()->setDiscountCode($this->getRequest()->cookies->get('simplytestable-signup-code'));

        return $this->getDiscountCodeService()->getDataForDiscountCode();
    }

    
    public function getCacheValidatorParameters() {        
        return [
            'user_create_error' => $this->getFlash('user_create_error', false),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation', false),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => $this->getRequestedPlan(),
            'redirect' => trim($this->getRequest()->query->get('redirect')),
            'discount_code' => json_encode($this->getDiscountCodeDataFromCookie())
        ];
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\DiscountCodeService
     */
    private function getDiscountCodeService() {
        return $this->container->get('simplytestable.services.discountCodeService');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\PlansService
     */
    private function getPlansService() {
        return $this->container->get('simplytestable.services.plansService');
    }

}