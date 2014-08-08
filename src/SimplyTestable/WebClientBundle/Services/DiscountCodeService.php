<?php
namespace SimplyTestable\WebClientBundle\Services;

class DiscountCodeService  {

    /**
     * @var string|null
     */
    private $discountCode = null;

    private $discountCodes = [];


    public function setDiscountCodes($discountCodes) {
        $this->discountCodes = $discountCodes;
    }


    /**
     * @param string $discountCode
     */
    public function setDiscountCode($discountCode) {
        $this->discountCode = $discountCode;
    }


    public function getDataForDiscountCode() {
        return [
            'code' => $this->discountCode,
            'active' => $this->isActive(),
            'has' => $this->has(),
            'intro' => $this->getIntro(),
            'percent_off' => $this->getPercentOff()
        ];

/**
'code' => $discountCode,
'active' => $this->getDiscountCodeService()->isActive($discountCode),
'has' => $this->getDiscountCodeService()->has($discountCode),
'intro' => $this->getDiscountCodeService()->getIntro($discountCode)
'percent_off'
 */
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

        $codeData = $this->discountCodes[$this->discountCode];

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
     * @param $discountCode
     * @return bool
     */
    public function has($discountCode = null) {
        $discountCode = (is_null($discountCode)) ? $this->discountCode : $discountCode;
        return isset($this->discountCodes[$discountCode]);
    }

}