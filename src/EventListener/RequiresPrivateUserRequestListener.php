<?php

namespace App\EventListener;

use App\Services\RequiresPrivateUserResponseProvider;
use App\Services\UrlMatcher;
use App\Services\UserManager;
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
     * @var RequiresPrivateUserResponseProvider
     */
    private $requiresPrivateUserResponseProvider;

    /**
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param RouterInterface $router
     * @param UrlMatcher $urlMatcher
     * @param RequiresPrivateUserResponseProvider $requiresPrivateUserResponseProvider
     */
    public function __construct(
        UserManager $userManager,
        SessionInterface $session,
        RouterInterface $router,
        UrlMatcher $urlMatcher,
        RequiresPrivateUserResponseProvider $requiresPrivateUserResponseProvider
    ) {
        $this->userManager = $userManager;
        $this->session = $session;
        $this->router = $router;
        $this->urlMatcher = $urlMatcher;
        $this->requiresPrivateUserResponseProvider = $requiresPrivateUserResponseProvider;
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

            $redirectResponse = $this->requiresPrivateUserResponseProvider->getResponse(
                $request->getMethod(),
                $requestPath
            );

            if (!empty($redirectResponse)) {
                $event->setResponse($redirectResponse);
            }
        }
    }
}
