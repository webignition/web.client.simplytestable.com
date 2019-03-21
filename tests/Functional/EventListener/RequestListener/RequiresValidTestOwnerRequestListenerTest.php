<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\RequiresValidTestOwnerRequestListener;
use App\Services\RemoteTestService;
use App\Tests\Services\ObjectReflector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidTestOwnerRequestListenerTest extends AbstractKernelRequestListenerTest
{
    const WEBSITE = 'http://example.com';
    const TEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresValidTestOwnerRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOnKernelController(bool $isAuthorised, bool $expectedHasResponse)
    {
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('isAuthorised')
            ->with(self::TEST_ID)
            ->andReturn($isAuthorised);

        ObjectReflector::setProperty(
            $this->requestListener,
            RequiresValidTestOwnerRequestListener::class,
            'remoteTestService',
            $remoteTestService
        );

        $router = self::$container->get(RouterInterface::class);

        $url = $router->generate(
            'view_test_results',
            [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ]
        );

        $event = $this->createGetResponseEvent(new Request(
            [],
            [],
            [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ],
            [],
            [],
            [
                'REQUEST_URI' => $url,
            ]
        ));

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());
    }

    public function dataProvider(): array
    {
        return [
            'invalid test' => [
                'isAuthorised' => false,
                'expectedHasResponse' => true,
            ],
            'invalid test owner' => [
                'isAuthorised' => false,
                'expectedHasResponse' => true,
            ],
            'valid test owner' => [
                'isAuthorised' => true,
                'expectedHasResponse' => false,
            ],
        ];
    }
}
