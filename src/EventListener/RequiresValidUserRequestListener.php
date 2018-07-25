<?php

namespace App\EventListener;

use App\Services\UrlMatcher;
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
     * @var UrlMatcher
     */
    private $urlMatcher;

    /**
     * @param UserService $userService
     * @param RouterInterface $router
     * @param UrlMatcher $urlMatcher
     */
    public function __construct(
        UserService $userService,
        RouterInterface $router,
        UrlMatcher $urlMatcher
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

        $requiresPrivateUser = $this->urlMatcher->match($request->getPathInfo());

        if ($requiresPrivateUser && !$this->userService->authenticate()) {
            $event->setResponse(new RedirectResponse($this->router->generate('action_user_sign_out')));
        }
    }
}
