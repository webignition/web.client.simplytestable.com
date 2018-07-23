<?php

namespace App\Model\User;

use App\Model\AbstractArrayBasedModel;
use App\Model\AccountPlan;

class Plan extends AbstractArrayBasedModel
{
    /**
     * @var float
     */
    private $priceModifier = 1;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $source)
    {
        parent::__construct($source);

        $this->setProperty('plan', new AccountPlan($this->getProperty('plan')));
    }

    /**
     * @return int
     */
    public function getStartTrialPeriod()
    {
        return $this->getProperty('start_trial_period');
    }

    /**
     * @return AccountPlan
     */
    public function getAccountPlan()
    {
        return $this->getProperty('plan');
    }

    /**
     * @return bool
     */
    public function hasTrialPeriodAvailable()
    {
        return $this->getStartTrialPeriod() > 0;
    }

    /**
     * @param float $priceModifier
     */
    public function setPriceModifier($priceModifier)
    {
        $this->priceModifier = $priceModifier;
    }

    /**
     * @return float
     */
    public function getPriceModifier()
    {
        return $this->priceModifier;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->getAccountPlan()->getPrice() * $this->getPriceModifier();
    }

    /**
     * @return float
     */
    public function getOriginalPrice()
    {
        return $this->getAccountPlan()->getPrice();
    }
}
