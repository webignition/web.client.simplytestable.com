<?php

namespace App\EventListener;

use App\Services\UrlMatcherInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidUserRequestListener
{
    private $userService;
    private $router;
    private $urlMatcher;

    public function __construct(
        UserService $userService,
        RouterInterface $router,
        UrlMatcherInterface $urlMatcher
    ) {
        $this->userService = $userService;
        $this->router = $router;
        $this->urlMatcher = $urlMatcher;
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

        $requiresValidUser = $this->urlMatcher->match($request->getPathInfo());

        if ($requiresValidUser && !$this->userService->authenticate()) {
            $event->setResponse(new RedirectResponse($this->router->generate('action_user_sign_out')));
        }
    }
}
