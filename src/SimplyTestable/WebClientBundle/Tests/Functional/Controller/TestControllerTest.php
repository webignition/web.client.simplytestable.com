<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\TaskController;
use SimplyTestable\WebClientBundle\Controller\TestController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Repository\TestRepository;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestControllerTest extends BaseSimplyTestableTestCase
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TestController
     */
    private $testController;

    /**
     * @var array
     */
    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [
            [
                'name' => Task::TYPE_HTML_VALIDATION,
            ],
        ],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testController = new TestController();
        $this->testController->setContainer($this->container);
    }

    /**
     * @dataProvider lockUnlockActionGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $routeName
     * @param array $routeParameters
     */
    public function testLockUnlockActionGetRequest(array $httpFixtures, $routeName, array $routeParameters)
    {
        $this->setHttpFixtures($httpFixtures);

        $router = $this->container->get('router');
        $requestUrl = $router->generate($routeName, $routeParameters);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/http://example.com//1/results/', $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function lockUnlockActionGetRequestDataProvider()
    {
        return [
            'lock action invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'routeName' => 'app_test_lock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unlock action invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'routeName' => 'app_test_unlock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'lock action valid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => 'app_test_lock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unlock action valid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => 'app_test_unlock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
        ];
    }
}
