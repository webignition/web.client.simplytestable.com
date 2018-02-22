<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractKernelControllerTest extends AbstractBaseTestCase
{
    /**
     * @param Request $request
     * @param Controller $controller
     * @param string $action
     * @param int $requestType
     *
     * @return FilterControllerEvent
     */
    protected function createFilterControllerEvent(
        Request $request,
        Controller $controller,
        $action,
        $requestType = HttpKernelInterface::MASTER_REQUEST
    ) {
        $callable = [
            $controller,
            $action
        ];

        return new FilterControllerEvent(
            $this->container->get('kernel'),
            $callable,
            $request,
            $requestType
        );
    }

    /**
     * @param RequiresCompletedTest|RequiresValidOwner|IEFiltered|RequiresPrivateUser|RequiresValidUser $controller
     * @param string $baseClassName
     *
     * @return Response|RedirectResponse
     *
     * @throws \ReflectionException
     */
    protected function getControllerResponse($controller, $baseClassName)
    {
        $reflectionClass = new ReflectionClass($baseClassName);

        $reflectionProperty = $reflectionClass->getProperty('response');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($controller);
    }
}
