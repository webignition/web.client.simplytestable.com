<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidUserRequestListener extends AbstractRequestListener
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param UserService $userService
     * @param RouterInterface $router
     */
    public function __construct(UserService $userService, RouterInterface $router)
    {
        $this->userService = $userService;
        $this->router = $router;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!parent::onKernelController($event)) {
            return;
        }

        if ($this->controller instanceof RequiresValidUser && !$this->userService->authenticate()) {
            $redirectUrl = $this->router->generate('sign_out_submit', [], UrlGeneratorInterface::ABSOLUTE_URL);

            /* @var RequiresValidUser $controller */
            $controller = $this->controller;
            $controller->setResponse(new RedirectResponse($redirectUrl));
        }
    }
}
