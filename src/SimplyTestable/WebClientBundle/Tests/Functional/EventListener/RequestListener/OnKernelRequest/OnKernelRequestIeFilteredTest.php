<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OnKernelRequestIeFilteredTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\View\User\SignUp\IndexController::indexAction';
    const CONTROLLER_ROUTE = 'view_user_signup_index_index';

    const IE6_USER_AGENT = 'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)';
    const IE7_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
    const OPERA_950_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; en) Opera 9.50';
    const IE8_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)';

    /**
     * @dataProvider dataProvider
     *
     * @param string $userAgent
     * @param bool $expectedIsRedirectResponse
     * @param string $expectedRedirectUrl
     *
     * @throws \Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testOnKernelRequest($userAgent, $expectedIsRedirectResponse, $expectedRedirectUrl = null)
    {
        $request = new Request();
        $request->headers->set('user-agent', $userAgent);

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE
        );

        $this->requestListener->onKernelRequest($event);

        $response = $event->getResponse();

        if ($expectedIsRedirectResponse) {
            /* @var RedirectResponse $response */
            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        } else {
            $this->assertNull($response);
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
                'expectedIsRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://test.simplytestable.com/',
            ],
            'IE7' => [
                'userAgent' => self::IE7_USER_AGENT,
                'expectedIsRedirectResponse' => true,
                'expectedRedirectUrl' => 'http://test.simplytestable.com/',
            ],
            'no user agent' => [
                'userAgent' => '',
                'expectedIsRedirectResponse' => false,
            ],
            'Opera 9.50' => [
                'userAgent' => self::OPERA_950_USER_AGENT,
                'expectedIsRedirectResponse' => false,
            ],
            'IE8' => [
                'userAgent' => self::IE8_USER_AGENT,
                'expectedIsRedirectResponse' => false,
],
        ];
    }
}
