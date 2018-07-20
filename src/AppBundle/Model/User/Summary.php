<?php
namespace AppBundle\Model\User;

use AppBundle\Model\AbstractArrayBasedModel;
use AppBundle\Model\User\Team\Summary as TeamSummary;
use webignition\Model\Stripe\Customer as StripeCustomerModel;

class Summary extends AbstractArrayBasedModel
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $source)
    {
        parent::__construct($source);

        if ($this->hasProperty('stripe_customer')) {
            $this->setProperty(
                'stripe_customer',
                new StripeCustomerModel(json_encode($this->getProperty('stripe_customer')))
            );
        }

        if ($this->hasProperty('user_plan')) {
            $this->setProperty('user_plan', new Plan($this->getProperty('user_plan')));
        }

        if ($this->hasProperty('team_summary')) {
            $this->setProperty('team_summary', new TeamSummary($this->getProperty('team_summary')));
        }
    }

    /**
     * @return StripeCustomerModel|null
     */
    public function getStripeCustomer()
    {
        return $this->getProperty('stripe_customer');
    }

    /**
     * @return Plan
     */
    public function getPlan()
    {
        return $this->getProperty('user_plan');
    }

    /**
     *
     * @return bool
     */
    public function hasPlan()
    {
        return !is_null($this->getPlan());
    }

    /**
     * @return bool
     */
    public function hasStripeCustomer()
    {
        return !is_null($this->getStripeCustomer());
    }

    /**
     * @return int
     */
    public function getDayOfTrialPeriod()
    {
        if (!$this->hasStripeCustomer()) {
            return null;
        }

        if (!$this->getStripeCustomer()->hasSubscription()) {
            return null;
        }

        if (!$this->getStripeCustomer()->getSubscription()->isTrialing()) {
            return null;
        }

        return (int)($this->getPlan()->getStartTrialPeriod() - floor($this->getTrialPeriodRemaining() / 86400));
    }

    /**
     * @return bool
     */
    private function hasDayOfTrialPeriod()
    {
        if (!$this->hasStripeCustomer()) {
            return false;
        }

        if (!$this->getStripeCustomer()->hasSubscription()) {
            return false;
        }

        if (!$this->getStripeCustomer()->getSubscription()->isTrialing()) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getTrialPeriodRemaining()
    {
        if (!$this->hasDayOfTrialPeriod()) {
            return $this->getPlan()->getStartTrialPeriod() * 86400;
        }

        return $this->getStripeCustomer()->getSubscription()->getTrialPeriod()->getEnd() - time();
    }

    /**
     * @return array
     */
    public function getPlanConstraints()
    {
        return $this->getProperty('plan_constraints');
    }

    /**
     * @return TeamSummary
     */
    public function getTeamSummary()
    {
        return $this->getProperty('team_summary');
    }
}
