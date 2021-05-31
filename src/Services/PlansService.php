<?php
namespace App\Services;

use App\Model\User\Plan;

class PlansService
{
    /**
     * @var float
     */
    private $priceModifier = 1;

    /**
     * @var array
     */
    private $plansData = [];

    /**
     * @var Plan[]
     */
    private $plans = null;

    /**
     * @var bool
     */
    private $premiumOnly = false;

    /**
     * @param $plans
     */
    public function setPlansData($plans)
    {
        $this->plansData = $plans;
    }

    /**
     * @return PlansService
     */
    public function listPremiumOnly()
    {
        $this->premiumOnly = true;

        return $this;
    }

    /**
     * @param float $priceModifier
     */
    public function setPriceModifier($priceModifier)
    {
        $this->priceModifier = $priceModifier;
    }

    /**
     * @return Plan[]
     */
    public function getList()
    {
        if (is_null($this->plans)) {
            $this->buildPlansList();
        }

        $plans = [];

        foreach ($this->plans as $plan) {
            if ($this->includePlanInList($plan)) {
                $plans[$plan->getAccountPlan()->getName()] = $plan;
            }
        }

        return $plans;
    }

    private function buildPlansList()
    {
        $this->plans = [];

        foreach ($this->plansData as $planData) {
            $plan = new Plan([
                'plan' => [
                    'name' => $planData['name'],
                    'is_premium' => $planData['price'] > 0,
                    'price' => $planData['price'],
                    'urls_per_job' => $planData['urls_per_job'],
                    'credits_per_month' => $planData['credits_per_month']
                ]
            ]);

            $plan->setPriceModifier($this->priceModifier);

            $this->plans[$planData['name']] = $plan;
        }
    }

    /**
     * @param Plan $plan
     *
     * @return bool
     */
    private function includePlanInList(Plan $plan)
    {
        if (!$this->premiumOnly) {
            return true;
        }

        return $plan->getAccountPlan()->getIsPremium();
    }
}
