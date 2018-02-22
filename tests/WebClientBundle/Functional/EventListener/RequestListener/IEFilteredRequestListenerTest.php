<?php

namespace Tests\WebClientBundle\Functional\EventListener\RequestListener;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\EventListener\IEFilteredRequestListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\IndexController;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IEFilteredRequestListenerTest extends AbstractKernelControllerTest
{
    const IE6_USER_AGENT = 'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)';
    const IE7_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
    const OPERA_950_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; en) Opera 9.50';
    const IE8_USER_AGENT = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)';

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

        $this->requestListener = $this->container->get(IEFilteredRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $userAgent
     * @param bool $expectedHasResponse
     *
     * @throws \ReflectionException
     */
    public function testOnKernelController($userAgent, $expectedHasResponse)
    {
        $request = new Request();
        $request->headers->set('user-agent', $userAgent);

        $controller = new IndexController();

        $event = $this->createFilterControllerEvent($request, $controller, 'indexAction');

        $this->requestListener->onKernelController($event);

        $this->assertEquals($expectedHasResponse, $controller->hasResponse());

        if ($expectedHasResponse) {
            $response = $this->getControllerResponse($controller, BaseViewController::class);

            $this->assertInstanceOf(RedirectResponse::class, $response);
            $this->assertEquals(
                $this->container->getParameter('marketing_site'),
                $response->getTargetUrl()
            );
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
            'no user agent' => [
                'userAgent' => '',
                'expectedHasResponse' => false,
            ],
            'Opera 9.50' => [
                'userAgent' => self::OPERA_950_USER_AGENT,
                'expectedHasResponse' => false,
            ],
            'IE8' => [
                'userAgent' => self::IE8_USER_AGENT,
                'expectedHasResponse' => false,
            ],
        ];
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

        $controller = new UserController();

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
}
