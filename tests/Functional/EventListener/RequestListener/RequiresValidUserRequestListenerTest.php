<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\RequiresValidUserRequestListener;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RequiresValidUserRequestListenerTest extends AbstractKernelRequestListenerTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresValidUserRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param $expectedHasResponse
     * @param string null $expectedRedirectUrl
     *
     * @throws \Exception
     */
    public function testOnKernelRequest(array $httpFixtures, $expectedHasResponse, $expectedRedirectUrl = null)
    {
        $this->assertTrue(true);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $event = $this->createGetResponseEvent(new Request());

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());

        if ($expectedHasResponse) {
            /* @var RedirectResponse $response */
            $response = $event->getResponse();

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'not authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedHasResponse' => true,
                'expectedRedirectUrl' => '/signout/',
            ],
            'authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }
}
