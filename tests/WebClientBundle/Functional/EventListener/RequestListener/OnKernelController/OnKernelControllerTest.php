<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener\OnKernelController;

use SimplyTestable\WebClientBundle\Controller\UserController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class OnKernelControllerTest extends AbstractOnKernelControllerTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param string $requestType
     *
     * @throws \Exception
     */
    public function testOnKernelRequestRequestType($requestType)
    {
        $request = new Request();

        $controller = new UserController();

        $event = $this->createFilterControllerEvent($request, $controller, 'signOutSubmitAction', $requestType);

        $this->requestListener->onKernelController($event);
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
