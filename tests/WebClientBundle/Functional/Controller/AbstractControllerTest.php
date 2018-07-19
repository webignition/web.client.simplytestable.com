<?php

namespace Tests\WebClientBundle\Functional\Controller;

use Symfony\Component\Routing\RouterInterface;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractControllerTest extends AbstractBaseTestCase
{
    /**
     * @var RouterInterface
     */
    protected $router;

    protected function setUp()
    {
        parent::setUp();

        $this->router = self::$container->get(RouterInterface::class);
    }
}
