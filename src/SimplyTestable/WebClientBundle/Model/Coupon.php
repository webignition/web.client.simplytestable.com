<?php
namespace SimplyTestable\WebClientBundle\Model;

class Coupon {

    /**
     * @var string
     */
    private $code = '';


    /**
     * @var bool
     */
    private $isActive = false;


    /**
     * @var string
     */
    private $intro = '';


    /**
     * @var int
     */
    private $percentOff = 0;


    /**
     * @param string $code
     * @return Coupon
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }


    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }


    /**
     * @return Coupon
     */
    public function setActive() {
        $this->isActive = true;
        return $this;
    }


    /**
     * @return Coupon
     */
    public function setInactive() {
        $this->isActive = false;
        return $this;
    }


    /**
     * @return bool
     */
    public function isActive() {
        return $this->isActive;
    }


    /**
     * @param string $intro
     * @return Coupon
     */
    public function setIntro($intro) {
        $this->intro = $intro;
        return $this;
    }


    /**
     * @return string
     */
    public function getIntro() {
        return $this->intro;
    }


    /**
     * @param $percentOff
     * @return Coupon
     */
    public function setPercentOff($percentOff) {
        $this->percentOff = $percentOff;
        return $this;
    }


    /**
     * @return int
     */
    public function getPercentOff() {
        return $this->percentOff;
    }


    /**
     * @return float
     */
    public function getPriceModifier() {
        return 1 - ($this->getPercentOff() / 100);
    }


    /**
     * @return string
     */
    public function __toString() {
        return json_encode([
            'code' => $this->getCode(),
            'active' => $this->isActive,
            'intro' => $this->getIntro(),
            'percent_off' => $this->getPercentOff()
        ]);
    }
    
}