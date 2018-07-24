<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\EventListener\IEFilteredRequestListener;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IEFilteredRequestListenerTest extends AbstractBaseTestCase
{
    const IE6_USER_AGENT = 'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)';
    const IE7_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
    const OPERA_950_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; en) Opera 9.50';
    const IE8_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)';
    const IE9_USER_AGENT = 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))';
    const IE10_USER_AGENT = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)';
    const CHROME_64_WINDOWS_USER_AGENT =
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) '
        .'Chrome/64.0.3282.186 Safari/537.36';

    /**
     * @var IEFilteredRequestListener
     */
    private $requestListener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(IEFilteredRequestListener::class);
    }

    public function testRequest()
    {
        $this->client->request('GET', '/', [], [], [
            'HTTP_USER_AGENT' => self::IE6_USER_AGENT,
        ]);

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(getenv('MARKETING_SITE'), $response->getTargetUrl());
    }

    public function testOnKernelRequestSubRequest()
    {
        $event = $this->createGetResponseEvent(new Request(), HttpKernelInterface::SUB_REQUEST);

        $this->requestListener->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    public function testOnKernelRequestPostRequest()
    {
        $request = new Request();
        $request->setMethod('POST');

        $event = $this->createGetResponseEvent($request);

        $this->requestListener->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $userAgent
     * @param bool $expectedHasResponse
     */
    public function testOnKernelRequest($userAgent, $expectedHasResponse)
    {
        $request = new Request();
        $request->headers->set('user-agent', $userAgent);

        $event = $this->createGetResponseEvent($request);

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());

        if ($expectedHasResponse) {
            /* @var RedirectResponse $response */
            $response = $event->getResponse();

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals(getenv('MARKETING_SITE'), $response->getTargetUrl());
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
            'IE6' => [
                'userAgent' => self::IE6_USER_AGENT,
                'expectedHasResponse' => true,
            ],
            'IE7' => [
                'userAgent' => self::IE7_USER_AGENT,
                'expectedHasResponse' => true,
            ],
            'IE8' => [
                'userAgent' => self::IE8_USER_AGENT,
                'expectedHasResponse' => true,
            ],
            'no user agent' => [
                'userAgent' => '',
                'expectedHasResponse' => false,
            ],
            'Opera 9.50' => [
                'userAgent' => self::OPERA_950_USER_AGENT,
                'expectedHasResponse' => false,
            ],
            'IE9' => [
                'userAgent' => self::IE9_USER_AGENT,
                'expectedHasResponse' => true,
            ],
            'IE10' => [
                'userAgent' => self::IE10_USER_AGENT,
                'expectedHasResponse' => false,
            ],
            'Chrome 64 Windows' => [
                'userAgent' => self::CHROME_64_WINDOWS_USER_AGENT,
                'expectedHasResponse' => false,
            ],
        ];
    }

    /**
     * @param Request $request
     * @param int $requestType
     *
     * @return GetResponseEvent
     */
    private function createGetResponseEvent(Request $request, int $requestType = HttpKernelInterface::MASTER_REQUEST)
    {
        return new GetResponseEvent(self::$container->get('kernel'), $request, $requestType);
    }
}
