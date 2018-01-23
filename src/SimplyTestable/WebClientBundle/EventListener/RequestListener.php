<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser as RequiresPrivateUserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser as RequiresValidUserController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner as RequiresValidTestOwnerController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest as RequiresCompletedTestController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable as CacheableController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered as IEFilteredController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RequestListener
{
    const APPLICATION_CONTROLLER_PREFIX = 'SimplyTestable\WebClientBundle\Controller\\';

    /**
     * @var GetResponseEvent
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
     * @var Controller
     */
    private $controller;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $this->kernel->getContainer();
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \Exception
     * @throws WebResourceException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::SUB_REQUEST) {
            return;
        }

        $this->event = $event;
        $this->request = $event->getRequest();

        if (!$this->isApplicationController()) {
            return;
        }

        $userService = $this->container->get('simplytestable.services.userservice');
        $controller = $this->createController();

        if ($controller instanceof IEFilteredController && $this->isUsingOldIE()) {
            $this->event->setResponse(
                new RedirectResponse($this->container->getParameter('public_site')['urls']['home'])
            );

            return;
        }

        $userService->setUserFromRequest($this->request);
        $requiresValidUserController =
            $controller instanceof RequiresValidUserController || $controller instanceof RequiresPrivateUserController;

        if ($requiresValidUserController && !$userService->authenticate()) {
            $router = $this->container->get('router');
            $redirectUrl = $router->generate('sign_out_submit', [], UrlGeneratorInterface::ABSOLUTE_URL);

            $this->event->setResponse(new RedirectResponse($redirectUrl));

            return;
        }

        if ($controller instanceof RequiresPrivateUserController && !$userService->isLoggedIn()) {
            $session = $this->container->get('session');

            $session->getFlashBag()->set('user_signin_error', 'account-not-logged-in');

            /* @var RequiresPrivateUserController $controller */
            $controller = $this->createController();

            $this->event->setResponse($controller->getUserSignInRedirectResponse($this->request));

            return;
        }

        if ($controller instanceof RequiresValidTestOwnerController) {
            $testService = $this->container->get('simplytestable.services.testservice');
            $requestAttributes = $this->request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');

            try {
                /* @var RequiresValidTestOwnerController $controller */
                $controller = $this->createController();

                if (!$testService->has($website, $testId)) {
                    $this->event->setResponse($controller->getInvalidOwnerResponse());

                    return;
                }

                $testService->get($website, $testId);
            } catch (WebResourceException $webResourceException) {
                /* @var RequiresValidTestOwnerController $controller */
                $controller = $this->createController();

                if ($webResourceException->getCode() == 403) {
                    $this->event->setResponse($controller->getInvalidOwnerResponse());

                    return;
                }

                throw $webResourceException;
            }
        }

        if ($controller instanceof RequiresCompletedTestController) {
            $testService = $this->container->get('simplytestable.services.testservice');
            $requestAttributes = $this->request->attributes;

            $website = $requestAttributes->get('website');
            $testId = $requestAttributes->get('test_id');

            /* @var RequiresCompletedTestController $controller */
            $controller = $this->createController();

            $controller->setRequest($this->event->getRequest());

            /* @var Test $test */
            $test = $testService->get($website, $testId);

            if ($test->getState() == Test::STATE_FAILED_NO_SITEMAP) {
                $this->event->setResponse($controller->getFailedNoSitemapTestResponse());

                return;
            }

            if ($test->getState() == Test::STATE_REJECTED) {
                $this->event->setResponse($controller->getRejectedTestResponse());

                return;
            }

            if (!$testService->isFinished($test)) {
                $this->event->setResponse($controller->getNotFinishedTestResponse());

                return;
            }

            if ($test->getWebsite() != $this->request->attributes->get('website')) {
                $this->event->setResponse($controller->getRequestWebsiteMismatchResponse());

                return;
            }
        }

        try {
            if ($controller instanceof CacheableController) {
                $cacheableResponseService = $this->container->get('simplytestable.services.cacheableresponseservice');
                $session = $this->container->get('session');

                $this->setRequestCacheValidatorHeaders();

                /* @var Response $response */
                $response = $cacheableResponseService->getCachableResponse($this->event->getRequest());

                $this->fixRequestIfNoneMatchHeader();

                if ($response->isNotModified($this->request)) {
                    $this->event->setResponse($response);
                    $session->getFlashBag()->clear();

                    return;
                }
            }
        } catch (\Exception $e) {
            // Exceptions may well be raised by a controller when getting cache validator parameters as the controller
            // can assume user and test authentication has already been carried out prior to the request reaching the
            // controller.
            // It is safe to ignore such exceptions.
            // It is much faster to try and return a 304 Not Modified response at this stage prior to any significant
            // processing occurring.
        }
    }

    private function setRequestCacheValidatorHeaders()
    {
        $cacheValidatorHeadersService = $this->container->get('simplytestable.services.cachevalidatorheadersservice');

        /* @var CacheableController $controller */
        $controller = $this->createController();

        $controller->setRequest($this->request);
        $cacheValidatorParameters = $controller->getCacheValidatorParameters();

        if ($this->request->headers->has('accept')) {
            $cacheValidatorHeaders['http-header-accept'] = $this->request->headers->get('accept');
        }

        $cacheValidatorIdentifier = $this->createCacheValidatorIdentifier($cacheValidatorParameters);

        /* @var CacheValidatorHeaders $cacheValidatorHeaders */
        $cacheValidatorHeaders = $cacheValidatorHeadersService->get($cacheValidatorIdentifier);

        $this->request->headers->set('x-cache-validator-etag', $cacheValidatorHeaders->getETag());
        $this->request->headers->set(
            'x-cache-validator-lastmodified',
            $cacheValidatorHeaders->getLastModifiedDate()->format('c')
        );
    }

    private function fixRequestIfNoneMatchHeader()
    {
        $currentIfNoneMatch = $this->request->headers->get('if-none-match');

        $modifiedEtag = preg_replace('/-gzip"$/', '"', $currentIfNoneMatch);

        $this->request->headers->set('if-none-match', $modifiedEtag);
    }

    /**
     * @return string
     */
    private function getControllerClassName()
    {
        $controllerActionParts = explode('::', $this->request->attributes->get('_controller'));
        return $controllerActionParts[0];
    }

    /**
     * @return bool
     */
    private function isApplicationController()
    {
        $controllerClassName = $this->getControllerClassName();
        $controllerClassNamePrefix = substr($controllerClassName, 0, strlen(self::APPLICATION_CONTROLLER_PREFIX));

        return $controllerClassNamePrefix == self::APPLICATION_CONTROLLER_PREFIX;
    }

    /**
     * @return Controller
     */
    private function createController()
    {
        if (is_null($this->controller)) {
            $className = $this->getControllerClassName();
            $this->controller = new $className;
            $this->controller->setContainer($this->container);
        }

        return $this->controller;
    }

    /**
     * @return bool
     */
    private function isUsingOldIE()
    {
        if ($this->isUsingIE6() || $this->isUsingIE7()) {
            $this->kernel->getContainer()->get('logger')->error(
                'Detected old IE for [' . $this->getUserAgentString() . ']'
            );
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function isUsingIE6()
    {
        if (preg_match('/msie 8.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }

        if (!preg_match('/msie 6\.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }

        if (preg_match('/opera/i', $this->getUserAgentString())) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isUsingIE7()
    {
        if (!preg_match('/msie 7\.[0-9]+/i', $this->getUserAgentString())) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function getUserAgentString()
    {
        $request = $this->event->getRequest();

        if ($request->headers->has('user-agent')) {
            return $request->headers->get('user-agent');
        }

        return $request->server->get('HTTP_USER_AGENT');
    }

    /**
     * @param array $parameters
     *
     * @return CacheValidatorIdentifier
     */
    private function createCacheValidatorIdentifier(array $parameters = [])
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $user = $userService->getUser();

        $identifier = new CacheValidatorIdentifier();
        $identifier->setParameter('route', $this->request->get('_route'));
        $identifier->setParameter('user', $user->getUsername());
        $identifier->setParameter('is_logged_in', $userService->isLoggedIn());

        foreach ($parameters as $key => $value) {
            $identifier->setParameter($key, $value);
        }

        return $identifier;
    }
}