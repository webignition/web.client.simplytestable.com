<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Dashboard;

use App\Controller\View\Dashboard\DashboardController;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class DashboardControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'dashboard.html.twig';
    const ROUTE_NAME = 'view_dashboard';
    const USER_EMAIL = 'user@example.com';

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $user = new User(self::USER_EMAIL);
        $userSerializer = self::$container->get(UserSerializer::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializer->serializeToString($user)
        ));

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        array $flashBagMessages,
        Request $request,
        Twig_Environment $twig
    ) {
        $userManager = self::$container->get(UserManager::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);
        $flashBag->setAll($flashBagMessages);

        /* @var DashboardController $dashboardController */
        $dashboardController = self::$container->get(DashboardController::class);
        $this->setTwigOnController($twig, $dashboardController);

        $response = $dashboardController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'public user' => [
                'httpFixtures' => [],
                'user' => SystemUserService::getPublicUser(),
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);

                            $this->assertEquals(null, $parameters['test_start_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user' => [
                'httpFixtures' => [],
                'user' => new User(self::USER_EMAIL),
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                                'link-integrity',
                            ]);

                            $this->assertEquals(null, $parameters['test_start_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user; has test start error' => [
                'httpFixtures' => [],
                'user' => new User(self::USER_EMAIL),
                'flashBagMessages' => [
                    'test_start_error' => ['website-blank'],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                                'link-integrity',
                            ]);

                            $this->assertEquals('website-blank', $parameters['test_start_error']);

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

        self::$container->get('request_stack')->push($request);

        /* @var DashboardController $dashboardController */
        $dashboardController = self::$container->get(DashboardController::class);

        $response = $dashboardController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $dashboardController->indexAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'available_task_types',
                'task_types',
                'test_options',
                'css_validation_ignore_common_cdns',
                'test_start_error',
                'website',
                'honeypot_field_name',
            ],
            array_keys($parameters)
        );
    }

    private function assertAvailableTaskTypeKeys(array $parameters, array $expectedAvailableTaskTypeKeys)
    {
        $availableTaskTypes = $parameters['available_task_types'];
        $this->assertEquals($expectedAvailableTaskTypeKeys, array_keys($availableTaskTypes));
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
