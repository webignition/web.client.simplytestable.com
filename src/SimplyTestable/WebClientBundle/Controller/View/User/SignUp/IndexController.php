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
        $viewData = [
            'user_create_error' => $this->getFlash('user_create_error'),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation'),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => trim($this->getRequest()->query->get('plan')),
            'redirect' => trim($this->getRequest()->query->get('redirect')),
            'has_discount_code' => $this->hasDiscountCode()
        ];

        if ($this->hasDiscountCode()) {
//            var_dump($this->getDiscountCodeDataFromCookie());
//            exit();

            $viewData['discount_code'] = $this->getDiscountCodeDataFromCookie();
        }

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
//
//        return [
//            'code' => $discountCode,
//            'active' => $this->getDiscountCodeService()->isActive($discountCode),
//            'has' => $this->getDiscountCodeService()->has($discountCode),
//            'intro' => $this->getDiscountCodeService()->getIntro($discountCode)
//        ];
    }

    
    public function getCacheValidatorParameters() {        
        return [
            'user_create_error' => $this->getFlash('user_create_error', false),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation', false),
            'email' => strtolower(trim($this->getRequest()->query->get('email'))),
            'plan' => trim($this->getRequest()->query->get('plan')),
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

}