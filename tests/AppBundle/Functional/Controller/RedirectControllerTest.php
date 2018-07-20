<?php

namespace Tests\AppBundle\Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use AppBundle\Controller\RedirectController;
use AppBundle\Entity\Test\Test;
use AppBundle\Services\RemoteTestService;
use AppBundle\Services\TestService;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Factory\TestFactory;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Tests\AppBundle\Services\HttpMockHandler;

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
            $this->router->generate('app_task_results_redirector', [
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
            $this->router->generate('app_test_redirector', [
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
            $this->router->generate('app_website', [
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
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param Request $request
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     */
    public function testTestAction(
        array $httpFixtures,
        array $testValues,
        Request $request,
        $website,
        $testId,
        $expectedRedirectUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            self::$container->get(TestService::class),
            self::$container->get(RemoteTestService::class),
            self::$container->get(EntityManagerInterface::class),
            self::$container->get(LoggerInterface::class),
            $request,
            $website,
            $testId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function dataProviderForTestAction()
    {
        return [
            'task results url without trailing slash' => [
                'httpFixtures' => [],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com//1/2/results',
                'testId' => null,
                'expectedRedirectUrl' => '/website/http://example.com//test_id/1/task_id/2/results/',
            ],
            'task results url with trailing slash' => [
                'httpFixtures' => [],
                'testValues' => [],
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
                'testValues' => [],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/http://example.com//99/',
            ],
            'posted website, no latest test, has test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com',
                    TestFactory::KEY_TEST_ID => 2,
                ],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/http://example.com//2/',
            ],
            'get website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
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
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => '/',
            ],
            'posted website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
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
                'testValues' => [],
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
                'testValues' => [],
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
                'testValues' => [],
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
                'testValues' => [],
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
                'testValues' => [],
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
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'no website, no test id' => [
                'httpFixtures' => [],
                'testValues' => [],
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