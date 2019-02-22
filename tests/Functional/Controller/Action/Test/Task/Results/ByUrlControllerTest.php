<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\Test\Task\Results;

use App\Controller\Action\Test\Task\Results\ByUrlController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;
use App\Tests\Services\HttpMockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Tests\Functional\Controller\AbstractControllerTest;

class ByUrlControllerTest extends AbstractControllerTest
{
    const WEBSITE_URL = 'http://example.com/';
    const TEST_ID = 1;
    const TASK_ID = 2;
    const TASK_URL = 'http://example.com/foo/';

    /**
     * @var ByUrlController
     */
    private $byUrlController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->byUrlController = self::$container->get(ByUrlController::class);

        $testFactory = new TestFactory(self::$container);

        $testValues = [
            TestFactory::KEY_TEST_ID => self::TEST_ID,
            TestFactory::KEY_TASKS => [
                [
                    TaskFactory::KEY_TASK_ID => self::TASK_ID,
                    TaskFactory::KEY_URL => self::TASK_URL,
                    TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                ],
            ],
        ];

        $testFactory->create($testValues);
    }

    public function testIndexActionGetRequest()
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            new Response(),
            HttpResponseFactory::createJsonResponse([
                'id' => self::TEST_ID,
                'website' => self::WEBSITE_URL,
                'task_types' => [
                    [
                        'name' => Task::TYPE_HTML_VALIDATION,
                    ],
                ],
                'user' => 'user@example.com',
                'state' => Test::STATE_COMPLETED,
                'task_type_options' => [],
                'task_count' => 12,
            ])
        ]);

        $this->client->request(
            'GET',
            $this->router->generate('action_test_task_results_byurl', [
                'website' => self::WEBSITE_URL,
                'test_id' => self::TEST_ID,
                'task_url' => self::TASK_URL,
                'task_type' => Task::TYPE_HTML_VALIDATION,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/2/results/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionDataProvider
     */
    public function testIndexAction(int $testId, string $taskUrl, string $taskType, string $expectedRedirectUrl)
    {
        /* @var RedirectResponse $response */
        $response = $this->byUrlController->indexAction(self::WEBSITE_URL, $testId, $taskUrl, $taskType);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionDataProvider(): array
    {
        return [
            'invalid test' => [
                'testId' => self::TEST_ID - 1,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => '/',
            ],
            'invalid task by url' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL . '/bar/',
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => '/http://example.com//1/',
            ],
            'invalid task by type' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'expectedRedirectUrl' => '/http://example.com//1/',
            ],
            'valid' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => '/http://example.com//1/2/results/',
            ],
        ];
    }
}
