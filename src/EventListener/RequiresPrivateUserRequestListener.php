<?php

namespace App\EventListener;

use App\Services\RequiresPrivateUserResponseProvider;
use App\Services\UrlMatcher;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresPrivateUserRequestListener
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

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

    public function __construct(
        UserManager $userManager,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        UrlMatcher $urlMatcher,
        RequiresPrivateUserResponseProvider $requiresPrivateUserResponseProvider
    ) {
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
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
            $this->flashBag->set('user_signin_error', 'account-not-logged-in');

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
