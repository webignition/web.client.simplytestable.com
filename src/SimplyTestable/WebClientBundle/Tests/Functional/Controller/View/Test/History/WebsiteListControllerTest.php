<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\View\Test\History\WebsiteListController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class WebsiteListControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'view_test_history_websitelist_index';

    /**
     * @var WebsiteListController
     */
    private $websiteListController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->websiteListController = new WebsiteListController();
        $this->websiteListController->setContainer($this->container);
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(404),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

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
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser(new User(UserService::PUBLIC_USER_USERNAME));

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        /* @var JsonResponse $response */
        $response = $this->websiteListController->indexAction();

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
