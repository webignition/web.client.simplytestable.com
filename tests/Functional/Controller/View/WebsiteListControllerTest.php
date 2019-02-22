<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View;

use App\Controller\View\WebsiteListController;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class WebsiteListControllerTest extends AbstractViewControllerTest
{
    const ROUTE_NAME = 'view_website_list';

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
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
     */
    public function testIndexAction(array $httpFixtures, array $expectedResponseData)
    {
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

    public function indexActionDataProvider(): array
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
