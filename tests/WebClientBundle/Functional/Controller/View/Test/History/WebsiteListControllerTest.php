<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\View\Test\History\WebsiteListController;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Services\HttpMockHandler;

class WebsiteListControllerTest extends AbstractBaseTestCase
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

        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'GET',
            $requestUrl
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

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'GET',
            $requestUrl
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
        $userManager = $this->container->get(UserManager::class);

        $user = SystemUserService::getPublicUser();
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $websiteListController = $this->container->get(WebsiteListController::class);

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
