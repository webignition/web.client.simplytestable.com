<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractEventController
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
