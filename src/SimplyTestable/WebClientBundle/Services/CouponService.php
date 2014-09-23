<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Coupon;

use Symfony\Component\HttpFoundation\Request as Request;

class CouponService {

    const COUPON_COOKIE_NAME = 'simplytestable-coupon-code';

    /**
     * @var Request
     */
    private $request;


    private $couponData = [];


    public function setCouponData($couponData) {
        $this->couponData = $couponData;
    }


    /**
     * @return null|Coupon
     */
    public function get() {
        if (!$this->has()) {
            return null;
        }

        $coupon = new Coupon();
        $coupon->setCode($this->getRequestCouponCode());
        $coupon->setIntro($this->getIntro());
        $coupon->setPercentOff($this->getPercentOff());

        if ($this->isActive()) {
            $coupon->setActive();
        }

        return $coupon;
    }


    /**
     * @return bool
     */
    private function isActive() {
        return $this->getProperty('active', false, function ($value) {
            return is_bool($value);
        });
    }


    /**
     * @return string
     */
    private function getIntro() {
        return $this->getProperty('intro', '');
    }


    /**
     * @return string
     */
    private function getPercentOff() {
        return $this->getProperty('percent_off', 0, function ($value) {
            return is_int($value);
        });
    }


    private function getProperty($name, $default = null, $validateCallback = null) {
        if (!$this->has()) {
            return $default;
        }

        $codeData = $this->couponData[$this->getRequestCouponCode()];

        if (!isset($codeData[$name])) {
            return $default;
        }

        if ($validateCallback instanceof \Closure) {
            if ($validateCallback($codeData[$name]) === false) {
                return $default;
            }
        }

        return $codeData[$name];
    }


    /**
     * @return bool
     */
    public function has() {
        return !is_null($this->getRequestCouponCode());
    }


    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null) {
        $this->request = $request;
    }


    /**
     * @return string|null
     */
    private function getRequestCouponCode() {
        return $this->request->cookies->get(self::COUPON_COOKIE_NAME);
    }

}