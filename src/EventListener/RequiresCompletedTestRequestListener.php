<?php

namespace App\EventListener;

use App\Services\TestService;
use App\Services\UrlMatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Test\Test;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresCompletedTestRequestListener
{
    private $urlMatcher;
    private $testService;
    private $router;

    public function __construct(UrlMatcherInterface $urlMatcher, TestService $testService, RouterInterface $router)
    {
        $this->urlMatcher = $urlMatcher;
        $this->testService = $testService;
        $this->router = $router;
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

        if (!$this->urlMatcher->match($request->getPathInfo())) {
            return;
        }

        $requestAttributes = $request->attributes;

        $website = $requestAttributes->get('website');
        $testId = $requestAttributes->get('test_id');

        $test = $this->testService->get($website, $testId);

        if (Test::STATE_FAILED_NO_SITEMAP === $test->getState()) {
            $event->setResponse(new RedirectResponse($this->router->generate(
                'view_test_results_failed_no_urls_detected',
                [
                    'website' => $website,
                    'test_id' => $testId
                ]
            )));

            return;
        }

        if (Test::STATE_REJECTED === $test->getState()) {
            $event->setResponse(new RedirectResponse($this->router->generate(
                'view_test_results_rejected',
                [
                    'website' => $website,
                    'test_id' => $testId
                ]
            )));

            return;
        }

        if (!$this->testService->isFinished($test)) {
            $event->setResponse(new RedirectResponse($this->router->generate(
                'view_test_progress',
                [
                    'website' => $website,
                    'test_id' => $testId
                ]
            )));

            return;
        }
    }
}
