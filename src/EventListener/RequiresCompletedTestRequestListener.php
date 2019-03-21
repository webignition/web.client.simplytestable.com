<?php

namespace App\EventListener;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\TestRetriever;
use App\Services\UrlMatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Test;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RequiresCompletedTestRequestListener
{
    const ROUTE_FAILED_NO_URLS_DETECTED = 'view_test_results_failed_no_urls_detected';
    const ROUTE_REJECTED = 'view_test_results_rejected';
    const ROUTE_PROGRESS = 'view_test_progress';

    private $urlMatcher;
    private $router;
    private $testRetriever;

    public function __construct(UrlMatcherInterface $urlMatcher, RouterInterface $router, TestRetriever $testRetriever)
    {
        $this->urlMatcher = $urlMatcher;
        $this->router = $router;
        $this->testRetriever = $testRetriever;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
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
        $route = $requestAttributes->get('_route');

        $website = $requestAttributes->get('website');
        $testId = (int) $requestAttributes->get('test_id');

        $test = $this->testRetriever->retrieve($testId);

        $isFailedNoUrlsDetectedRequest = self::ROUTE_FAILED_NO_URLS_DETECTED === $route;
        $isRejectedRequest = self::ROUTE_REJECTED === $route;
        $isProgressRequest = self::ROUTE_PROGRESS === $route;

        if (Test::STATE_FAILED_NO_SITEMAP === $test->getState() && !$isFailedNoUrlsDetectedRequest) {
            $event->setResponse(new RedirectResponse($this->router->generate(
                'view_test_results_failed_no_urls_detected',
                [
                    'website' => $website,
                    'test_id' => $testId
                ]
            )));

            return;
        }

        if (Test::STATE_REJECTED === $test->getState() && !$isRejectedRequest) {
            $event->setResponse(new RedirectResponse($this->router->generate(
                'view_test_results_rejected',
                [
                    'website' => $website,
                    'test_id' => $testId
                ]
            )));

            return;
        }

        if (!$test->isFinished() && !$isProgressRequest) {
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
