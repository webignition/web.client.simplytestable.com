<?php
namespace App\Services;

use App\Model\Coupon;

use Symfony\Component\HttpFoundation\Request as Request;

class CouponService
{
    const COUPON_COOKIE_NAME = 'simplytestable-coupon-code';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $couponData = [];

    /**
     * @param $couponData
     */
    public function setCouponData($couponData)
    {
        $this->couponData = $couponData;
    }

    /**
     * @return null|Coupon
     */
    public function get()
    {
        if (!$this->has()) {
            return null;
        }

        $coupon = new Coupon();
        $coupon->setCode($this->getRequestCouponCode());
        $coupon->setIntro($this->getIntro());
        $coupon->setPercentOff($this->getPercentOff());
        $coupon->setIsActive($this->isActive());

        return $coupon;
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        return $this->getProperty('active', false);
    }

    /**
     * @return string
     */
    private function getIntro()
    {
        return $this->getProperty('intro', '');
    }

    /**
     * @return string
     */
    private function getPercentOff()
    {
        return $this->getProperty('percent_off', 0);
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    private function getProperty($name, $default = null)
    {
        $couponData = $this->couponData[$this->getRequestCouponCode()];

        if (!isset($couponData[$name])) {
            return $default;
        }

        return $couponData[$name];
    }

    /**
     * @return bool
     */
    public function has()
    {
        $couponCode = $this->getRequestCouponCode();

        if (empty($couponCode)) {
            return false;
        }

        return isset($this->couponData[$couponCode]);
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return string|null
     */
    private function getRequestCouponCode()
    {
        return $this->request->cookies->get(self::COUPON_COOKIE_NAME);
    }
}
