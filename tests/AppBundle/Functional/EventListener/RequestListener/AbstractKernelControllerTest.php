<?php

namespace Tests\AppBundle\Functional\EventListener\RequestListener;

use ReflectionClass;
use AppBundle\Controller\Action\User\UserController;
use AppBundle\Controller\View\Dashboard\IndexController;
use AppBundle\EventListener\IEFilteredRequestListener;
use AppBundle\EventListener\RequiresCompletedTestRequestListener;
use AppBundle\EventListener\RequiresPrivateUserRequestListener;
use AppBundle\EventListener\RequiresValidTestOwnerRequestListener;
use AppBundle\EventListener\RequiresValidUserRequestListener;
use AppBundle\Interfaces\Controller\IEFiltered;
use AppBundle\Interfaces\Controller\RequiresPrivateUser;
use AppBundle\Interfaces\Controller\RequiresValidUser;
use AppBundle\Interfaces\Controller\SettableResponse;
use AppBundle\Interfaces\Controller\Test\RequiresCompletedTest;
use AppBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tests\AppBundle\Services\HttpMockHandler;

abstract class AbstractKernelControllerTest extends AbstractBaseTestCase
{
    /**
     * @var IEFilteredRequestListener|RequiresCompletedTestRequestListener|RequiresPrivateUserRequestListener|RequiresValidTestOwnerRequestListener|RequiresValidUserRequestListener
     */
    protected $requestListener;

    /**
     * @var HttpMockHandler
     */
    protected $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

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
            self::$container->get('kernel'),
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

        $controller = self::$container->get(UserController::class);

        $event = $this->createFilterControllerEvent($request, $controller, 'signOutSubmitAction', $requestType);

        $this->assertNull($this->requestListener->onKernelController($event));
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
        $controller = self::$container->get(IndexController::class);
        $controller->setResponse(new Response());

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->assertNull($this->requestListener->onKernelController($event));
    }
}
