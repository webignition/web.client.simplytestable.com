<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Controller\View\Dashboard\IndexController;
use SimplyTestable\WebClientBundle\EventListener\IEFilteredRequestListener;
use SimplyTestable\WebClientBundle\EventListener\RequiresCompletedTestRequestListener;
use SimplyTestable\WebClientBundle\EventListener\RequiresPrivateUserRequestListener;
use SimplyTestable\WebClientBundle\EventListener\RequiresValidTestOwnerRequestListener;
use SimplyTestable\WebClientBundle\EventListener\RequiresValidUserRequestListener;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\SettableResponse;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractKernelControllerTest extends AbstractBaseTestCase
{
    /**
     * @var IEFilteredRequestListener|RequiresCompletedTestRequestListener|RequiresPrivateUserRequestListener|RequiresValidTestOwnerRequestListener|RequiresValidUserRequestListener
     */
    protected $requestListener;

    /**
     * @param Request $request
     * @param $controller
     * @param string $action
     * @param int $requestType
     *
     * @return FilterControllerEvent
     */
    protected function createFilterControllerEvent(
        Request $request,
        $controller,
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

    /**
     * @dataProvider requestTypeDataProvider
     *
     * @param string $requestType
     *
     * @throws \Exception
     */
    public function testOnKernelControllerRequestType($requestType)
    {
        $request = new Request();

        $controller = $this->container->get(UserController::class);

        $event = $this->createFilterControllerEvent($request, $controller, 'signOutSubmitAction', $requestType);

        $this->requestListener->onKernelController($event);
    }

    /**
     * @return array
     */
    public function requestTypeDataProvider()
    {
        return [
            'sub request' => [
                'requestType' => HttpKernelInterface::SUB_REQUEST
            ],
            'master request' => [
                'requestType' => HttpKernelInterface::MASTER_REQUEST
            ],
        ];
    }

    public function testOnKernelControllerHasResponse()
    {
        $request = new Request();

        /* @var SettableResponse $controller */
        $controller = $this->container->get(IndexController::class);
        $controller->setResponse(new Response());

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);
    }
}
