<?php

namespace AppBundle\Model;

class AccountPlan extends AbstractArrayBasedModel
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getProperty('name');
    }

    /**
     * @return bool
     */
    public function getIsPremium()
    {
        return $this->getProperty('is_premium');
    }

    /**
     * @return bool
     */
    public function getIsCustom()
    {
        return preg_match('/-custom$/', $this->getName()) > 0;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return (int)$this->getProperty('price');
    }

    /**
     * @return int
     */
    public function getUrlsPerJob()
    {
        return (int)$this->getProperty('urls_per_job');
    }

    /**
     * @return int
     */
    public function getCreditsPerMonth()
    {
        return (int)$this->getProperty('credits_per_month');
    }
}
