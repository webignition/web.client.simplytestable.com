<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\Routing\RouterInterface;

abstract class AbstractController
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
}
