<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\TaskController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskControllerTest extends BaseSimplyTestableTestCase
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TaskController
     */
    private $taskController;

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

        $this->taskController = new TaskController();
        $this->taskController->setContainer($this->container);
    }

    public function testIdCollectionActionInvalidOwner()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');

        $requestUrl = $router->generate('app_task_ids', [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals([], $responseData);
    }

    public function testUnretrievedIdCollectionActionInvalidOwner()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');

        $requestUrl = $router->generate('app_unretrieved_task_ids', [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'limit' => TaskController::DEFAULT_UNRETRIEVED_TASKID_LIMIT,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals([], $responseData);
    }

    public function testIdCollectionActionRender()
    {
        $taskIds = [1, 2, 3, 4,];

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
        ]);

        /* @var JsonResponse $response */
        $response = $this->taskController->idCollectionAction(self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($taskIds, $responseData);
    }

    /**
     * @dataProvider unretrievedIdCollectionActionRenderDataProvider
     *
     * @param int $limit
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testUnretrievedIdCollectionActionRender($limit)
    {
        $taskIds = [1, 2, 3, 4,];

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
        ]);

        /* @var JsonResponse $response */
        $response = $this->taskController->unretrievedIdCollectionAction(
            self::WEBSITE,
            self::TEST_ID,
            $limit
        );

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($taskIds, $responseData);
    }

    /**
     * @return array
     */
    public function unretrievedIdCollectionActionRenderDataProvider()
    {
        return [
            'no specified limit' => [
                'limit' => null,
            ],
            'limit exceeds maximum' => [
                'limit' => TaskController::MAX_UNRETRIEVED_TASKID_LIMIT + 1,
            ],
        ];
    }
}
