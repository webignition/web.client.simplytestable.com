<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Interfaces\Controller\Test\RequiresValidOwner;
use AppBundle\Services\TestService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RequiresValidTestOwnerRequestListener extends AbstractRequestListener
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @param TestService $testService
     */
    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!parent::onKernelController($event)) {
            return;
        }

        if ($this->controller instanceof RequiresValidOwner) {
            $request = $event->getRequest();

            $requestAttributes = $request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');

            /* @var RequiresValidOwner $controller */
            $controller = $this->controller;

            try {
                if (!$this->testService->has($website, $testId)) {
                    $controller->setResponse($controller->getInvalidOwnerResponse($request));

                    return;
                }

                $test = $this->testService->get($website, $testId);

                if (empty($test)) {
                    throw new InvalidCredentialsException();
                }
            } catch (InvalidCredentialsException $invalidCredentialsException) {
                $controller->setResponse($controller->getInvalidOwnerResponse($request));

                return;
            }
        }
    }
}
