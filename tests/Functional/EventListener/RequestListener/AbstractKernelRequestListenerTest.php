<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\IEFilteredRequestListener;
use App\EventListener\RequiresPrivateUserRequestListener;
use App\EventListener\RequiresValidUserRequestListener;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractKernelRequestListenerTest extends AbstractBaseTestCase
{
    /**
     * @var IEFilteredRequestListener|RequiresValidUserRequestListener|RequiresPrivateUserRequestListener
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

    public function testOnKernelRequestSubRequest()
    {
        $event = $this->createGetResponseEvent(new Request(), HttpKernelInterface::SUB_REQUEST);

        $this->requestListener->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    /**
     * @param Request $request
     * @param int $requestType
     *
     * @return GetResponseEvent
     */
    protected function createGetResponseEvent(Request $request, int $requestType = HttpKernelInterface::MASTER_REQUEST)
    {
        return new GetResponseEvent(self::$container->get('kernel'), $request, $requestType);
    }
}
