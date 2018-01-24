<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\FailedNoUrlsDetected;

use SimplyTestable\WebClientBundle\Controller\View\Test\Results\FailedNoUrlsDetected\IndexController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends BaseSimplyTestableTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Results/FailedNoUrlsDetected/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_results_failednourlsdetected_index_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

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

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(404),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param User $user
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws WebResourceException
     */
    public function testIndexActionBadRequest(
        array $httpFixtures,
        User $user,
        Request $request,
        $website,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        $httpHistory = new HttpHistory($this->container->get('simplytestable.services.httpclientservice'));

        $this->setHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());
        }
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
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Ffoo.example.com%2F/1/',
                ],
            ],
            'incorrect state' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
            'not public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(self::USER_EMAIL),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param EngineInterface $templatingEngine
     *
     * @throws WebResourceException
     */
    public function testIndexActionRender(EngineInterface $templatingEngine)
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'simplytestable.services.testservice',
                'simplytestable.services.remotetestservice',
                'simplytestable.services.userservice',
                'simplytestable.services.cachevalidator',
                'simplytestable.services.urlviewvalues',
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

        $response = $this->indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'default' => [
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        $this->container->set('request', $request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

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
                'public_site',
                'external_links',
                'website',
                'redirect',
            ],
            array_keys($parameters)
        );
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