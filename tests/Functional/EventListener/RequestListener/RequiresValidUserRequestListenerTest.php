<?php
/** @noinspection PhpDocSignatureInspection */

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
     */
    public function testOnKernelRequest(
        array $httpFixtures,
        bool $expectedHasResponse,
        ?string $expectedRedirectUrl = null
    ) {
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

    public function dataProvider(): array
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
