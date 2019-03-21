<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Model\Test as TestModel;
use App\Controller\View\Partials\TestUrlLimitNotificationController;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use webignition\SimplyTestableUserModel\User;

class TestUrlLimitNotificationControllerTest extends AbstractViewControllerTest
{
    const ROUTE_NAME = 'view_partials_test_url_limit_notification';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEmpty($response->getContent());
    }

    /**
     * @dataProvider indexActionGetRequestDataProvider
     */
    public function testIndexActionGetRequest(
        User $user,
        array $remoteTestData,
        bool $expectedResponseHasContent,
        array $expectedContentContains = []
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessful());

        if ($expectedResponseHasContent) {
            $content = $response->getContent();

            foreach ($expectedContentContains as $expectedContains) {
                $this->assertContains($expectedContains, $content);
            }
        } else {
            $this->assertEmpty($response->getContent());
        }
    }

    public function indexActionGetRequestDataProvider(): array
    {
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User(self::USER_EMAIL);

        $remoteTestData = [
            'id' => self::TEST_ID,
            'website' => self::WEBSITE,
            'task_types' => [],
            'user' => self::USER_EMAIL,
            'state' => TestModel::STATE_COMPLETED,
        ];

        return [
            'no ammendments' => [
                'user' => $publicUser,
                'remoteTestData' => $remoteTestData,
                'expectedResponseHasContent' => false,
            ],
            'public user' => [
                'user' => $publicUser,
                'remoteTestData' => array_merge($remoteTestData, [
                    'user' => 'public',
                    'ammendments' => [
                        [
                            'constraint' => [
                                'limit' => 10,
                            ],
                            'reason' => 'plan-url-limit-reached:discovered-url-count-12',
                        ],
                    ],
                ]),
                'expectedResponseHasContent' => true,
                'expectedContentContains' => [
                    '<strong>free demo</strong>',
                    '<strong>10</strong>',
                    '<strong>12</strong>',
                    '<a href="/signup/">Create an account</a> or <a href="/signin/">sign in</a>',
                ],
            ],
            'private user, public test' => [
                'user' => $privateUser,
                'remoteTestData' => array_merge($remoteTestData, [
                    'user' => 'public',
                    'ammendments' => [
                        [
                            'constraint' => [
                                'limit' => 10,
                            ],
                            'reason' => 'plan-url-limit-reached:discovered-url-count-12',
                        ],
                    ],
                ]),
                'expectedResponseHasContent' => true,
                'expectedContentContains' => [
                    '<strong>free demo</strong>',
                    '<strong>10</strong>',
                    '<strong>12</strong>',
                    'Start a new test or <a href="/account/">upgrade your plan</a>',
                ],
            ],
            'private user, private test' => [
                'user' => $privateUser,
                'remoteTestData' => array_merge($remoteTestData, [
                    'user' => self::USER_EMAIL,
                    'ammendments' => [
                        [
                            'constraint' => [
                                'limit' => 100,
                            ],
                            'reason' => 'plan-url-limit-reached:discovered-url-count-250',
                        ],
                    ],
                ]),
                'expectedResponseHasContent' => true,
                'expectedContentContains' => [
                    'This test',
                    '<strong>100</strong>',
                    '<strong>250</strong>',
                    'Start a new test or <a href="/account/">upgrade your plan</a>',
                ],
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $remoteTestData = [
            'id' => self::TEST_ID,
            'website' => self::WEBSITE,
            'task_types' => [],
            'user' => self::USER_EMAIL,
            'state' => TestModel::STATE_COMPLETED,
            'ammendments' => [
                [
                    'constraint' => [
                        'limit' => 10,
                    ],
                    'reason' => 'plan-url-limit-reached:discovered-url-count-12',
                ],
            ],
        ];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $request = new Request();

        /* @var TestUrlLimitNotificationController $urlLimitController */
        $urlLimitController = self::$container->get(TestUrlLimitNotificationController::class);

        $response = $urlLimitController->indexAction($request, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $urlLimitController->indexAction($newRequest, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }
}
