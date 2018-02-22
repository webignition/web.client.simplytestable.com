<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Kernel;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser as RequiresPrivateUserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner as RequiresValidTestOwnerController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest as RequiresCompletedTestController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class RequestListener
{
    const APPLICATION_CONTROLLER_PREFIX = 'SimplyTestable\WebClientBundle\Controller\\';

    /**
     * @var FilterControllerEvent
     */
    private $event;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $this->kernel->getContainer();
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @throws CoreApplicationRequestException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->event = $event;
        $this->request = $event->getRequest();

        /* @var Controller $controller */
        $controller = $this->event->getController()[0];

        if (!$this->isApplicationController($controller)) {
            return;
        }

        $userManager = $this->container->get(UserManager::class);

        if ($controller instanceof RequiresPrivateUserController && !$userManager->isLoggedIn()) {
            /* @var RequiresPrivateUserController $controller */

            $session = $this->container->get('session');
            $router = $this->container->get('router');

            $session->getFlashBag()->set('user_signin_error', 'account-not-logged-in');

            $controller->setResponse($controller->getUserSignInRedirectResponse($router, $this->request));

            return;
        }

        if ($controller instanceof RequiresValidTestOwnerController) {
            /* @var RequiresValidTestOwnerController $controller */

            $testService = $this->container->get(TestService::class);
            $requestAttributes = $this->request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');

            try {
                if (!$testService->has($website, $testId)) {
                    $controller->setResponse($controller->getInvalidOwnerResponse($this->request));

                    return;
                }

                $test = $testService->get($website, $testId);

                if (empty($test)) {
                    throw new InvalidCredentialsException();
                }
            } catch (InvalidCredentialsException $invalidCredentialsException) {
                $controller->setResponse($controller->getInvalidOwnerResponse($this->request));

                return;
            }
        }

        if ($controller instanceof RequiresCompletedTestController) {
            /* @var RequiresCompletedTestController $controller */

            $testService = $this->container->get(TestService::class);
            $router = $this->container->get('router');
            $requestAttributes = $this->request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');


            /* @var Test $test */
            $test = $testService->get($website, $testId);

            if ($test->getState() == Test::STATE_FAILED_NO_SITEMAP) {
                $controller->setResponse($controller->getFailedNoSitemapTestResponse($router, $this->request));

                return;
            }

            if ($test->getState() == Test::STATE_REJECTED) {
                $controller->setResponse($controller->getRejectedTestResponse($router, $this->request));

                return;
            }

            if (!$testService->isFinished($test)) {
                $controller->setResponse($controller->getNotFinishedTestResponse($router, $this->request));

                return;
            }

            if ($test->getWebsite() != $this->request->attributes->get('website')) {
                $controller->setResponse($controller->getRequestWebsiteMismatchResponse($router, $this->request));

                return;
            }
        }
    }

    /**
     * @param Controller $controller
     *
     * @return bool
     */
    private function isApplicationController(Controller $controller)
    {
        $controllerClassName = get_class($controller);
        $controllerClassNamePrefix = substr($controllerClassName, 0, strlen(self::APPLICATION_CONTROLLER_PREFIX));

        return $controllerClassNamePrefix == self::APPLICATION_CONTROLLER_PREFIX;
    }
}
