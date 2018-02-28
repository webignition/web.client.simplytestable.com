<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword\ChooseController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ChooseControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Choose:index.html.twig';
    const ROUTE_NAME = 'view_user_resetpassword_choose_index';

    const USER_EMAIL = 'user@example.com';
    const TOKEN = 'token-value';

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse(self::TOKEN),
            HttpResponseFactory::createSuccessResponse(),
        ]);

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
     * @param array $httpFixtures
     * @param array $flashBagValues
     * @param Request $request
     * @param string $token
     * @param Twig_Environment $twig
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagValues,
        Request $request,
        $token,
        Twig_Environment $twig
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        /* @var ChooseController $chooseController */
        $chooseController = $this->container->get(ChooseController::class);
        $this->setTwigOnController($twig, $chooseController);

        $response = $chooseController->indexAction($request, self::USER_EMAIL, $token);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagValues' => [],
                'request' => new Request(),
                'token' => 'invalid-token',
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals('invalid-token', $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertEquals('invalid-token', $parameters['user_reset_password_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'invalid token, has password reset error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagValues' => [
                    IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                ],
                'request' => new Request(),
                'token' => 'invalid-token',
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals('invalid-token', $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertEquals('invalid-token', $parameters['user_reset_password_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token, has password reset error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY =>
                        IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                ],
                'request' => new Request(),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertEquals(
                                IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                                $parameters['user_reset_password_error']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagValues' => [],
                'request' => new Request(),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertNull($parameters['user_reset_password_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token, stay-signed-in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagValues' => [],
                'request' => new Request([
                    'stay-signed-in' => 1,
                ]),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);
                            $this->assertNull($parameters['user_reset_password_error']);

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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse(self::TOKEN),
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);

        /* @var ChooseController $chooseController */
        $chooseController = $this->container->get(ChooseController::class);

        $response = $chooseController->indexAction(
            $request,
            self::USER_EMAIL,
            self::TOKEN
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $chooseController->indexAction(
            $newRequest,
            self::USER_EMAIL,
            self::TOKEN
        );

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

        $this->assertEquals(self::USER_EMAIL, $parameters['email']);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'email',
                'token',
                'stay_signed_in',
                'user_reset_password_error',
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

        return $router->generate(self::ROUTE_NAME, [
            'email' => self::USER_EMAIL,
            'token' => self::TOKEN,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
