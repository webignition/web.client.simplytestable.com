<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\Test\Task\Results;

use SimplyTestable\WebClientBundle\Controller\Action\Test\Task\Results\ByUrlController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ByUrlControllerTest extends BaseSimplyTestableTestCase
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

        $this->byUrlController = new ByUrlController();
        $this->byUrlController->setContainer($this->container);

        $testFactory = new TestFactory($this->container);

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
        $router = $this->container->get('router');
        $requestUrl = $router->generate('action_test_task_results_byurl_index', [
            'website' => self::WEBSITE_URL,
            'test_id' => self::TEST_ID,
            'task_url' => self::TASK_URL,
            'task_type' => Task::TYPE_HTML_VALIDATION,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/http://example.com//1/2/results/'));
    }

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param int $testId
     * @param string $taskUrl
     * @param string $taskType
     * @param string $expectedRedirectUrl
     */
    public function testIndexAction($testId, $taskUrl, $taskType, $expectedRedirectUrl)
    {
        /* @var RedirectResponse $response */
        $response = $this->byUrlController->indexAction(self::WEBSITE_URL, $testId, $taskUrl, $taskType);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        return [
            'invalid test' => [
                'testId' => self::TEST_ID - 1,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'invalid task by url' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL . '/bar/',
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
            ],
            'invalid task by type' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
            ],
            'valid' => [
                'testId' => self::TEST_ID,
                'taskUrl' => self::TASK_URL,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/2/results/',
            ],
        ];
    }
}
