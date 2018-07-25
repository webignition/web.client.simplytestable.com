<?php

namespace App\EventListener;

use App\Services\RequiresValidUserUrlMatcher;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidUserRequestListener
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
     * @var RequiresValidUserUrlMatcher
     */
    private $requiresValidUserUrlMatcher;

    /**
     * @param UserService $userService
     * @param RouterInterface $router
     * @param RequiresValidUserUrlMatcher $requiresValidUserUrlMatcher
     */
    public function __construct(
        UserService $userService,
        RouterInterface $router,
        RequiresValidUserUrlMatcher $requiresValidUserUrlMatcher
    ) {
        $this->userService = $userService;
        $this->router = $router;
        $this->requiresValidUserUrlMatcher = $requiresValidUserUrlMatcher;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $requiresPrivateUser = $this->requiresValidUserUrlMatcher->match($request->getPathInfo());

        if ($requiresPrivateUser && !$this->userService->authenticate()) {
            $event->setResponse(new RedirectResponse($this->router->generate('action_user_sign_out')));
        }
    }
}
