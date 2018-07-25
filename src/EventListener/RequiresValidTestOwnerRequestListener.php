<?php

namespace App\EventListener;

use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\Test\RequiresValidOwner;
use App\Services\RequiresValidTestOwnerResponseProvider;
use App\Services\TestService;
use App\Services\UrlMatcher;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequiresValidTestOwnerRequestListener extends AbstractRequestListener
{
//    /**
//     * @var TestService
//     */
//    private $testService;

    /**
     * @var UrlMatcher
     */
    private $urlMatcher;

    /**
     * @var RequiresValidTestOwnerResponseProvider
     */
    private $requiresValidTestOwnerResponseProvider;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TestService
     */
    private $testService;

    public function __construct(
        UrlMatcher $urlMatcher,
        RequiresValidTestOwnerResponseProvider $requiresValidTestOwnerResponseProvider,
        UserManager $userManager,
        SessionInterface $session,
        TestService $testService
    ) {
        $this->urlMatcher = $urlMatcher;
        $this->requiresValidTestOwnerResponseProvider = $requiresValidTestOwnerResponseProvider;
        $this->userManager = $userManager;
        $this->session = $session;
        $this->testService = $testService;
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
        $requiresValidTestOwner = $this->urlMatcher->match($requestPath);

        $requestAttributes = $request->attributes;
        $website = $requestAttributes->get('website');
        $testId = $requestAttributes->get('test_id');

        if ($requiresValidTestOwner && !$this->testService->get($website, $testId)) {
            $response = $this->requiresValidTestOwnerResponseProvider->getResponse($request);

            if (!empty($response)) {
                if (!$this->userManager->isLoggedIn()) {
                    $this->session->getFlashBag()->set('user_signin_error', 'test-not-logged-in');
                }

                $event->setResponse($response);
            }
        }
    }
}
