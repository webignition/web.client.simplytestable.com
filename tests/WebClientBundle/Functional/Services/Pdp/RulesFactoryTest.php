<?php

namespace Tests\WebClientBundle\Functional\Services\Pdp;

use Pdp\Rules;
use SimplyTestable\WebClientBundle\Services\Pdp\RulesFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class RulesFactoryTest extends AbstractBaseTestCase
{
    /**
     * @var RulesFactory
     */
    private $rulesFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->rulesFactory = $this->container->get(RulesFactory::class);
    }

    public function testCreate()
    {
        $rules = $this->rulesFactory->create();

        $this->assertInstanceOf(Rules::class, $rules);
    }
}
