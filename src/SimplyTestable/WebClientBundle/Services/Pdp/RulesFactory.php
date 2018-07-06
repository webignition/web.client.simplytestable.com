<?php

namespace SimplyTestable\WebClientBundle\Services\Pdp;

use Pdp\Manager;
use Pdp\Rules;

class RulesFactory
{
    /**
     * @var Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Rules
     */
    public function create()
    {
        return $this->manager->getRules();
    }
}
