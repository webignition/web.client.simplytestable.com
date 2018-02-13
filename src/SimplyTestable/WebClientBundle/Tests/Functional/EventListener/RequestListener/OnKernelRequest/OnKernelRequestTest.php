<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class OnKernelRequestTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION = 'foo';
    const CONTROLLER_ROUTE = 'foo';

    /**
     * @dataProvider dataProvider
     *
     * @param string $requestType
     *
     * @throws \Exception
     */
    public function testOnKernelRequest($requestType)
    {
        $request = new Request();

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE,
            null,
            $requestType
        );

        $this->requestListener->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * @return array
     */
    public function dataProvider()
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
}
