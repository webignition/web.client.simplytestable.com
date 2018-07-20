<?php

namespace AppBundle\EventListener;

use AppBundle\Interfaces\Controller\RequiresPrivateUser;
use AppBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AppBundle\Interfaces\Controller\RequiresPrivateUser as RequiresPrivateUserController;
use Symfony\Component\Routing\RouterInterface;

class RequiresPrivateUserRequestListener extends AbstractRequestListener
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param RouterInterface $router
     */
    public function __construct(UserManager $userManager, SessionInterface $session, RouterInterface $router)
    {
        $this->userManager = $userManager;
        $this->session = $session;
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

        if ($this->controller instanceof RequiresPrivateUserController && !$this->userManager->isLoggedIn()) {
            $this->session->getFlashBag()->set('user_signin_error', 'account-not-logged-in');

            /* @var RequiresPrivateUser $controller */
            $controller = $this->controller;

            $controller->setResponse(
                $controller->getUserSignInRedirectResponse($this->router, $event->getRequest())
            );
        }
    }
}
