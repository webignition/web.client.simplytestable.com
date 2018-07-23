<?php

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\FailedNoUrlsDetectedController;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class FailedNoUrlsDetectedControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results-failed-no-urls-detected.html.twig';
    const ROUTE_NAME = 'view_test_results_failednourlsdetected_index_index';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var array
     */
    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

    /**
     * @var array
     */
    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_FAILED_NO_SITEMAP,
        'task_type_options' => [],
    ];

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse()
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $website
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     */
    public function testIndexActionBadRequest(
        array $httpFixtures,
        User $user,
        Request $request,
        $website,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var FailedNoUrlsDetectedController $indexController */
        $indexController = self::$container->get(FailedNoUrlsDetectedController::class);

        $response = $indexController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function indexActionBadRequestDataProvider()
    {
        return [
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => '/http://example.com//1/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Ffoo.example.com%2F/1/',
            ],
            'incorrect state' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'not public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(self::USER_EMAIL),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param Twig_Environment $twig
     * @throws CoreApplicationRequestException
     */
    public function testIndexActionRender(Twig_Environment $twig)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        /* @var FailedNoUrlsDetectedController $indexController */
        $indexController = self::$container->get(FailedNoUrlsDetectedController::class);
        $this->setTwigOnController($twig, $indexController);

        $response = $indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'default' => [
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertInternalType('string', $parameters['redirect']);

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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var FailedNoUrlsDetectedController $indexController */
        $indexController = self::$container->get(FailedNoUrlsDetectedController::class);

        $response = $indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $indexController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
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
                'website',
                'redirect',
            ],
            array_keys($parameters)
        );
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