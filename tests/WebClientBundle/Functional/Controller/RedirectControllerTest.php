<?php

namespace Tests\WebClientBundle\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\RedirectController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectControllerTest extends AbstractBaseTestCase
{
    const USERNAME = 'user@example.com';

    /**
     * @var RedirectController
     */
    protected $redirectController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->redirectController = $this->container->get(RedirectController::class);
    }

    /**
     * @dataProvider dataProviderForTestAction
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param Request $request
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     */
    public function testTestAction(
        array $httpFixtures,
        array $testValues,
        Request $request,
        $website,
        $testId,
        $expectedRedirectUrl
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        /* @var RedirectResponse $response */
        $response = $this->redirectController->testAction(
            $this->container->get(TestService::class),
            $this->container->get(RemoteTestService::class),
            $this->container->get('doctrine.orm.entity_manager'),
            $this->container->get('logger'),
            $request,
            $website,
            $testId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function dataProviderForTestAction()
    {
        return [
            'task results url without trailing slash' => [
                'httpFixtures' => [],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com//1/2/results',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/website/http://example.com//test_id/1/task_id/2/results/',
            ],
            'task results url with trailing slash' => [
                'httpFixtures' => [],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com//3/4/results/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/website/http://example.com//test_id/3/task_id/4/results/',
            ],
            'posted website, has latest test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 99,
                        'website' => 'http://example.com/'
                    ]),
                ],
                'testValues' => [],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//99/',
            ],
            'posted website, no latest test, has test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com',
                    TestFactory::KEY_TEST_ID => 2,
                ],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//2/',
            ],
            'get website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request([
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'no website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'posted website, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request([], [
                    'website' => 'http://example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'posted website, no scheme, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request([], [
                    'website' => 'example.com/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'posted website, no scheme, no host, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request([], [
                    'website' => '/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'posted website, no id, no latest test, does not have test in repository' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request([], [
                    'website' => 'http://example.com/1/',
                ]),
                'website' => 'http://example.com/',
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//',
            ],
            'website and test_id, has latest test, http error retrieving remote test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//',
            ],
            'website and test_id, has latest test, success retrieving remote test, test finished' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
            ],
            'website and test_id, has latest test, success retrieving remote test, test in progress' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_IN_PROGRESS,
                    ]),
                ],
                'testValues' => [],
                'request' => new Request(),
                'website' => 'http://example.com/',
                'testId' => 1,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
            ],
            'no website, no test id' => [
                'httpFixtures' => [],
                'testValues' => [],
                'request' => new Request(),
                'website' => null,
                'testId' => null,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
        ];
    }

    public function testTaskAction()
    {
        /* @var RedirectResponse $response */
        $response = $this->redirectController->taskAction(
            'http://example.com/',
            1,
            2
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/http://example.com//1/2/results/', $response->getTargetUrl());
    }
}
