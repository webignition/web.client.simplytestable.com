<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;

class TestServiceTest extends AbstractCoreApplicationServiceTest
{
    const USERNAME = 'user@example.com';

    /**
     * @var TestService
     */
    private $testService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testService = $this->container->get(
            'simplytestable.services.testservice'
        );

        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $remoteTestService->setUser(new User(self::USERNAME));
    }

    /**
     * @dataProvider hasDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param string $canonicalUrl
     * @param int $testId
     * @param bool $expectedHas
     *
     * @throws WebResourceException
     */
    public function testHas($httpFixtures, $testValues, $canonicalUrl, $testId, $expectedHas)
    {
        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $has = $this->testService->has($canonicalUrl, $testId);

        $this->assertEquals($expectedHas, $has);
    }

    /**
     * @return array
     */
    public function hasDataProvider()
    {
        return [
            'has locally' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => true,
            ],
            'has remotely' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => true,
            ],
            'not has remotely' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => false,
            ],
        ];
    }
}
