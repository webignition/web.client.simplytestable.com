<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Partial\Notification;

use SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification\UrlLimitController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends BaseSimplyTestableTestCase
{
    const INDEX_ACTION_VIEW_NAME =
        'SimplyTestableWebClientBundle:bs3/Test/Partial/Notification/UrlLimit:index.html.twig';
    const ROUTE_NAME = 'view_test_partial_notification_urlimit_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var UrlLimitController
     */
    private $indexController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new UrlLimitController();
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

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createForbiddenResponse(),
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

        $this->assertTrue($response->isSuccessful());
        $this->assertEmpty($response->getContent());
    }

    /**
     * @dataProvider indexActionGetRequestDataProvider
     *
     * @param User $user
     * @param array $remoteTestData
     * @param bool $expectedResponseHasContent
     * @param array $expectedContentContains
     */
    public function testIndexActionGetRequestFoo(
        User $user,
        array $remoteTestData,
        $expectedResponseHasContent,
        array $expectedContentContains = []
    ) {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse($remoteTestData),
            HttpResponseFactory::createJsonResponse($remoteTestData),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
            $userSerializerService->serializeToString($user)
        ));

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

    /**
     * @return array
     */
    public function indexActionGetRequestDataProvider()
    {
        $publicUser = new User(UserService::PUBLIC_USER_USERNAME);
        $privateUser = new User(self::USER_EMAIL);

        $remoteTestData = [
            'id' => self::TEST_ID,
            'website' => self::WEBSITE,
            'task_types' => [],
            'user' => self::USER_EMAIL,
            'state' => Test::STATE_COMPLETED,
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
}
