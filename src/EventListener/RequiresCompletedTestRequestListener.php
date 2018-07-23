<?php

namespace App\EventListener;

use App\Services\TestService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use App\Interfaces\Controller\Test\RequiresCompletedTest;
use App\Entity\Test\Test;
use Symfony\Component\Routing\RouterInterface;

class RequiresCompletedTestRequestListener extends AbstractRequestListener
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TestService $testService
     * @param RouterInterface $router
     */
    public function __construct(TestService $testService, RouterInterface $router)
    {
        $this->testService = $testService;
        $this->router = $router;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!parent::onKernelController($event)) {
            return;
        }

        if ($this->controller instanceof RequiresCompletedTest) {
            $request = $event->getRequest();

            $requestAttributes = $request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');

            $test = $this->testService->get($website, $testId);

            /* @var RequiresCompletedTest $controller */
            $controller = $this->controller;

            if (Test::STATE_FAILED_NO_SITEMAP === $test->getState()) {
                $controller->setResponse($controller->getFailedNoSitemapTestResponse($this->router, $request));

                return;
            }

            if (Test::STATE_REJECTED === $test->getState()) {
                $controller->setResponse($controller->getRejectedTestResponse($this->router, $request));

                return;
            }

            if (!$this->testService->isFinished($test)) {
                $controller->setResponse($controller->getNotFinishedTestResponse($this->router, $request));

                return;
            }

            if ($test->getWebsite() != $website) {
                $controller->setResponse($controller->getRequestWebsiteMismatchResponse($this->router, $request));

                return;
            }
        }
    }
}
