<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\FailedNoUrlsDetectedController;
use App\Entity\Test;
use App\Services\SystemUserService;
use App\Services\TestService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;
use webignition\SimplyTestableUserModel\User;

class FailedNoUrlsDetectedControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results-failed-no-urls-detected.html.twig';
    const ROUTE_NAME = 'view_test_results_failed_no_urls_detected';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_FAILED_NO_SITEMAP,
        'task_type_options' => [],
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

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

        $this->assertEquals(
            [
                'http://null/user/public/authenticate/',
                'http://null/job/1/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     */
    public function testIndexActionBadRequest(
        User $user,
        string $website,
        string $expectedRedirectUrl
    ) {
        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var FailedNoUrlsDetectedController $failedNoUrlsDetectedController */
        $failedNoUrlsDetectedController = self::$container->get(FailedNoUrlsDetectedController::class);

        $testService = $this->createTestService($website, self::TEST_ID, $test);
        $this->setTestServiceOnController($failedNoUrlsDetectedController, $testService);

        $response = $failedNoUrlsDetectedController->indexAction(new Request(), $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionBadRequestDataProvider(): array
    {
        return [
            'website mismatch' => [
                'user' => SystemUserService::getPublicUser(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'incorrect state' => [
                'user' => SystemUserService::getPublicUser(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'not public user' => [
                'user' => new User(self::USER_EMAIL),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(Twig_Environment $twig)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        /* @var FailedNoUrlsDetectedController $failedNoUrlsDetectedController */
        $failedNoUrlsDetectedController = self::$container->get(FailedNoUrlsDetectedController::class);
        $this->setTwigOnController($twig, $failedNoUrlsDetectedController);

        $response = $failedNoUrlsDetectedController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'default' => [
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertIsArray($parameters['website']);
                            $this->assertIsString($parameters['redirect']);

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

        /* @var FailedNoUrlsDetectedController $failedNoUrlsDetectedController */
        $failedNoUrlsDetectedController = self::$container->get(FailedNoUrlsDetectedController::class);

        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));
        $test->setState(TestService::STATE_FAILED_NO_SITEMAP);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test);
        $this->setTestServiceOnController($failedNoUrlsDetectedController, $testService);

        $response = $failedNoUrlsDetectedController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $failedNoUrlsDetectedController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

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
     * @param string $website
     * @param int $testId
     * @param Test $test
     *
     * @return TestService|MockInterface
     */
    private function createTestService(string $website, int $testId, Test $test)
    {
        $testService = \Mockery::mock(TestService::class);
        $testService
            ->shouldReceive('get')
            ->with($website, $testId)
            ->andReturn($test);

        return $testService;
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
