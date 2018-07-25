<?php

namespace App\EventListener;

use App\Services\RequiresPrivateUserRedirectRouteProvider;
use App\Services\UrlMatcher;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresPrivateUserRequestListener
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
     * @var UrlMatcher
     */
    private $urlMatcher;

    /**
     * @var RequiresPrivateUserRedirectRouteProvider
     */
    private $requiresPrivateUserRedirectRouteProvider;

    /**
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param RouterInterface $router
     * @param UrlMatcher $urlMatcher
     * @param RequiresPrivateUserRedirectRouteProvider $requiresPrivateUserRedirectRouteProvider
     */
    public function __construct(
        UserManager $userManager,
        SessionInterface $session,
        RouterInterface $router,
        UrlMatcher $urlMatcher,
        RequiresPrivateUserRedirectRouteProvider $requiresPrivateUserRedirectRouteProvider
    ) {
        $this->userManager = $userManager;
        $this->session = $session;
        $this->router = $router;
        $this->urlMatcher = $urlMatcher;
        $this->requiresPrivateUserRedirectRouteProvider = $requiresPrivateUserRedirectRouteProvider;
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
        $requestPath = $request->getPathInfo();
        $requiresPrivateUser = $this->urlMatcher->match($requestPath);

        if ($requiresPrivateUser && !$this->userManager->isLoggedIn()) {
            $this->session->getFlashBag()->set('user_signin_error', 'account-not-logged-in');

            $redirectRoute = $this->requiresPrivateUserRedirectRouteProvider->getRouteForUrlPathPattern(
                $this->urlMatcher->getMatchPattern($requestPath),
                $request->getMethod(),
                $requestPath
            );

            if (!empty($redirectRoute)) {
                $signInRedirectResponse = new RedirectResponse($this->router->generate(
                    'view_user_sign_in',
                    [
                        'redirect' => base64_encode(json_encode(['route' => $redirectRoute]))
                    ]
                ));

                $event->setResponse($signInRedirectResponse);
            }
        }
    }
}
