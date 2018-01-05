<?php
namespace SimplyTestable\WebClientBundle\Model;

class Coupon implements \JsonSerializable
{
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
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param $percentOff
     */
    public function setPercentOff($percentOff)
    {
        $this->percentOff = $percentOff;
    }

    /**
     * @return int
     */
    public function getPercentOff()
    {
        return $this->percentOff;
    }

    /**
     * @return float
     */
    public function getPriceModifier()
    {
        return 1 - ($this->getPercentOff() / 100);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->getCode(),
            'active' => $this->isActive(),
            'intro' => $this->getIntro(),
            'percent_off' => $this->getPercentOff()
        ];
    }
}
