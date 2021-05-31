<?php

namespace App\Tests\Functional\Services\Pdp;

use Pdp\Rules;
use App\Services\Pdp\RulesFactory;
use App\Tests\Functional\AbstractBaseTestCase;

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

        $this->rulesFactory = self::$container->get(RulesFactory::class);
    }

    public function testCreate()
    {
        $rules = $this->rulesFactory->create();

        $this->assertInstanceOf(Rules::class, $rules);
    }
}
