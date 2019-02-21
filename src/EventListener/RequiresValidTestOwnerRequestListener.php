<?php

namespace App\EventListener;

use App\Services\RequiresValidTestOwnerResponseProvider;
use App\Services\TestService;
use App\Services\UrlMatcherInterface;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequiresValidTestOwnerRequestListener
{
    private $urlMatcher;
    private $requiresValidTestOwnerResponseProvider;
    private $userManager;
    private $flashBag;
    private $testService;

    public function __construct(
        UrlMatcherInterface $urlMatcher,
        RequiresValidTestOwnerResponseProvider $requiresValidTestOwnerResponseProvider,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        TestService $testService
    ) {
        $this->urlMatcher = $urlMatcher;
        $this->requiresValidTestOwnerResponseProvider = $requiresValidTestOwnerResponseProvider;
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
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
                    $this->flashBag->set('user_signin_error', 'test-not-logged-in');
                }

                $event->setResponse($response);
            }
        }
    }
}
