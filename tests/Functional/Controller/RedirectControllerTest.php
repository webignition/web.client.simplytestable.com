<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller;

use App\Exception\CoreApplicationRequestException;
use App\Model\TestIdentifier;
use App\Services\TestRetriever;
use App\Tests\Factory\TestModelFactory;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use App\Controller\RedirectController;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Services\RemoteTestService;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Services\HttpMockHandler;

class RedirectControllerTest extends AbstractControllerTest
{
    const USERNAME = 'user@example.com';
    const WEBSITE = 'http://example.com/';

    /**
     * @var RedirectController
     */
    protected $redirectController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->redirectController = self::$container->get(RedirectController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testTaskActionGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate('redirect_task', [
                'website' => 'http://example.com',
                'test_id' => 1,
                'task_id' => 2,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com/1/2/results/', $response->getTargetUrl());
    }

    public function testTestActionGetRequestHasTestId()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'website' => 'http://example.com/',
                'task_types' => [],
                'user' => self::USERNAME,
                'state' => TestModel::STATE_COMPLETED,
            ]),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate('redirect_website_test', [
                'website' => 'http://example.com/',
                'test_id' => 1,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/results/', $response->getTargetUrl());
    }

    public function testTestActionGetRequestNoTestId()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'website' => 'http://example.com/',
                'task_types' => [],
                'user' => self::USERNAME,
                'state' => TestModel::STATE_COMPLETED,
            ]),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate('redirect_website', [
                'website' => 'http://example.com/',
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/', $response->getTargetUrl());
    }


    /**
     * @dataProvider dataProviderForTestActionTaskResultsUrl
     */
    public function testTestActionTaskResultsUrl(
        string $website,
        string $expectedRedirectUrl
    ) {
        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            \Mockery::mock(TestRetriever::class),
            \Mockery::mock(RemoteTestService::class),
            new Request(),
            $website,
            1
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function dataProviderForTestActionTaskResultsUrl(): array
    {
        return [
            'task results url without trailing slash' => [
                'website' => 'http://example.com//1/2/results',
                'expectedRedirectUrl' => '/website/http://example.com//test_id/1/task_id/2/results/',
            ],
            'task results url with trailing slash' => [
                'website' => 'http://example.com//3/4/results/',
                'expectedRedirectUrl' => '/website/http://example.com//test_id/3/task_id/4/results/',
            ],
        ];
    }

    public function testTestActionWebsiteOnlyUsesLatest()
    {
        $testIdentifier = new TestIdentifier(99, self::WEBSITE);

        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('retrieveLatest')
            ->with(self::WEBSITE)
            ->andReturn($testIdentifier);

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            \Mockery::mock(TestRetriever::class),
            $remoteTestService,
            new Request([], [
                'website' => self::WEBSITE,
            ]),
            self::WEBSITE,
            null
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//99/', $response->getTargetUrl());
    }

    /**
     * @dataProvider dataProviderForTestActionWebsiteOnlyNoMatchingTest
     */
    public function testTestActionWebsiteOnlyNoMatchingTest(Request $request)
    {
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('retrieveLatest')
            ->with(self::WEBSITE)
            ->andReturnNull();

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            \Mockery::mock(TestRetriever::class),
            $remoteTestService,
            $request,
            self::WEBSITE,
            null
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function dataProviderForTestActionWebsiteOnlyNoMatchingTest(): array
    {
        return [
            'get website, no latest test, does not have test in repository' => [
                'request' => new Request([
                    'website' => self::WEBSITE,
                ]),
            ],
            'no website, no latest test, does not have test in repository' => [
                'request' => new Request(),
            ],
            'posted website, no latest test, does not have test in repository' => [
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                ]),
            ],
            'posted website, no scheme, no latest test, does not have test in repository' => [
                'request' => new Request([], [
                    'website' => 'example.com/',
                ]),
            ],
            'posted website, no scheme, no host, no latest test, does not have test in repository' => [
                'request' => new Request([], [
                    'website' => '/',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestActionWebsiteAndTestIdUnableToRetrieveLatest
     */
    public function testTestActionWebsiteAndTestIdUnableToRetrieveLatestTest(Request $request, ?int $testId)
    {
        $coreApplicationRequestException = new CoreApplicationRequestException(
            new BadResponseException(
                '',
                \Mockery::mock(RequestInterface::class),
                new Response(404)
            )
        );

        $testRetriever = \Mockery::mock(TestRetriever::class);
        $testRetriever
            ->shouldReceive('retrieve')
            ->andThrow($coreApplicationRequestException);

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            $testRetriever,
            \Mockery::mock(RemoteTestService::class),
            $request,
            self::WEBSITE,
            $testId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//', $response->getTargetUrl());
    }

    public function dataProviderForTestActionWebsiteAndTestIdUnableToRetrieveLatest(): array
    {
        return [
            'posted website, no id, no latest test, does not have test in repository' => [
                'request' => new Request([], [
                    'website' => 'http://example.com/1/',
                ]),
                'testId' => null,
            ],
            'website and test_id, has latest test, http error retrieving remote test' => [
                'request' => new Request(),
                'testId' => 1,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestActionWebsiteAndTestHasTest
     */
    public function testTestActionWebsiteAndTestHasTest(string $testState, string $expectedRedirectUrl)
    {
        $testId = 1;

        $test = TestModelFactory::create([
            'entity' => TestEntity::create($testId),
            'state' => $testState,
        ]);

        $testRetriever = \Mockery::mock(TestRetriever::class);
        $testRetriever
            ->shouldReceive('retrieve')
            ->with($testId)
            ->andReturn($test);

        /* @var RemoteTestService $remoteTestService */
        $remoteTestService = self::$container->get(RemoteTestService::class);

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            $testRetriever,
            $remoteTestService,
            new Request(),
            self::WEBSITE,
            $testId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function dataProviderForTestActionWebsiteAndTestHasTest(): array
    {
        return [
            'website and test_id, has latest test, success retrieving remote test, test finished' => [
                'testState' => TestModel::STATE_COMPLETED,
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
            'website and test_id, has latest test, success retrieving remote test, test in progress' => [
                'testState' => TestModel::STATE_IN_PROGRESS,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
        ];
    }

    public function testTaskAction()
    {
        /* @var RedirectResponse $response */
        $response = $this->redirectController->taskAction(
            'http://example.com/',
            1,
            2
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/2/results/', $response->getTargetUrl());
    }
}
