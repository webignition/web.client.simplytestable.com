<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Controller\RedirectController;
use App\Entity\Test;
use App\Services\RemoteTestService;
use App\Services\TestService;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Services\HttpMockHandler;

class RedirectControllerTest extends AbstractControllerTest
{
    const USERNAME = 'user@example.com';

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
                'state' => Test::STATE_COMPLETED,
            ]),
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
                'state' => Test::STATE_COMPLETED,
            ]),
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
     * @dataProvider dataProviderForTestAction
     */
    public function testTestAction(
        array $httpFixtures,
        Request $request,
        ?string $website,
        ?int $testId,
        string $expectedRedirectUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var TestService $testService */
        $testService = self::$container->get(TestService::class);

        /* @var RemoteTestService $remoteTestService */
        $remoteTestService = self::$container->get(RemoteTestService::class);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        /* @var LoggerInterface $logger */
        $logger = self::$container->get(LoggerInterface::class);

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            $testService,
            $remoteTestService,
            $entityManager,
            $logger,
            $request,
            $website,
            $testId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function dataProviderForTestAction(): array
    {
        return [
            'task results url without trailing slash' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'website' => 'http://example.com//1/2/results',
                'testId' => null,
                'expectedRedirectUrl' => '/website/http://example.com//test_id/1/task_id/2/results/',
            ],
            'task results url with trailing slash' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'website' => 'http://example.com//3/4/results/',
                'testId' => null,
                'expectedRedirectUrl' => '/website/http://example.com//test_id/3/task_id/4/results/',
            ],
            'posted website, has latest test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 99,
                        'website' => 'http://example.com/'
                    ]),
                ],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/http://example.com//99/',
            ],
            'get website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'no website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'posted website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'posted website, no scheme, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'website' => 'example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'posted website, no scheme, no host, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'website' => '/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'posted website, no id, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'website' => 'http://example.com/1/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/http://example.com//',
            ],
            'website and test_id, has latest test, http error retrieving remote test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => '/http://example.com//',
            ],
            'website and test_id, has latest test, success retrieving remote test, test finished' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
            'website and test_id, has latest test, success retrieving remote test, test in progress' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_IN_PROGRESS,
                    ]),
                ],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'no website, no test id' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'website' => null,
                'testId' => null,
                'expectedRedirectUrl' => '/',
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
