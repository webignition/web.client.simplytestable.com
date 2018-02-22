<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

abstract class AbstractRequestListener
{
    const APPLICATION_CONTROLLER_PREFIX = 'SimplyTestable\WebClientBundle\Controller\\';

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->controller = $event->getController()[0];
    }

    /**
     * @return bool
     */
    protected function isApplicationController()
    {
        $controllerClassName = get_class($this->controller);
        $controllerClassNamePrefix = substr($controllerClassName, 0, strlen(self::APPLICATION_CONTROLLER_PREFIX));

        return self::APPLICATION_CONTROLLER_PREFIX === $controllerClassNamePrefix;
    }
}
