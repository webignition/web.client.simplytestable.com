<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\ResetPassword;

//use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController
    as ResetPasswordActionController;

class IndexControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Index:index.html.twig';
    const ROUTE_NAME = 'view_user_resetpassword_index_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $flashBagValues
     * @param Request $request
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testIndexActionRender(
        array $flashBagValues,
        Request $request,
        EngineInterface $templatingEngine
    ) {
        $session = $this->container->get('session');

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'simplytestable.services.cachevalidator',
                'simplytestable.services.userservice',
                'simplytestable.services.flashbagvalues',
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'no email' => [
                'flashBagValues' => [],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals('', $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has email' => [
                'flashBagValues' => [],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has user_reset_password_error' => [
                'flashBagValues' => [
                    ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_KEY =>
                        ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                ],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertEquals(
                                ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                                $parameters['user_reset_password_error']
                            );
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has user_reset_password_confirmation' => [
                'flashBagValues' => [
                    ResetPasswordActionController::FLASH_BAG_REQUEST_SUCCESS_KEY =>
                        ResetPasswordActionController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                ],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertEquals(
                                ResetPasswordActionController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                                $parameters['user_reset_password_confirmation']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $request = new Request();

        $this->container->set('request', $request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertCommonViewData($viewName, $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'public_site',
                'external_links',
                'email',
            ],
            array_keys($parameters)
        );
    }


    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
