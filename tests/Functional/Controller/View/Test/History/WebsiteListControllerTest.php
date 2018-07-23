<?php

namespace App\Tests\Functional\Controller\View\Test\History;

use App\Controller\View\Test\History\WebsiteListController;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;

class WebsiteListControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'view_test_history_websitelist_index';

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

        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
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
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param string[] $expectedResponseData
     */
    public function testIndexAction(
        array $httpFixtures,
        array $expectedResponseData
    ) {
        $userManager = self::$container->get(UserManager::class);

        $user = SystemUserService::getPublicUser();
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $websiteListController = self::$container->get(WebsiteListController::class);

        /* @var JsonResponse $response */
        $response = $websiteListController->indexAction();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedResponseData, json_decode($response->getContent()));
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        return [
            'empty list' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'expectedResponseData' => [],
            ],
            'non-empty list' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'http://example.com/',
                        'http://foo.example.com/',
                        'http://bar.example.com/',
                    ]),
                ],
                'expectedResponseData' => [
                    'http://example.com/',
                    'http://foo.example.com/',
                    'http://bar.example.com/',
                ],
            ],
        ];
    }
}
