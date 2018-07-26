<?php

namespace App\Tests\Functional\EventListener\RequestListener;

use App\Entity\Test\Test;
use App\EventListener\RequiresValidTestOwnerRequestListener;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidTestOwnerRequestListenerTest extends AbstractKernelRequestListenerTest
{
    const WEBSITE = 'http://example.com';
    const TEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = self::$container->get(RequiresValidTestOwnerRequestListener::class);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasResponse
     */
    public function testOnKernelController(array $httpFixtures, array $testValues, $expectedHasResponse)
    {
        $router = self::$container->get(RouterInterface::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        $url = $router->generate(
            'view_test_results',
            [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ]
        );

        $event = $this->createGetResponseEvent(new Request(
            [],
            [],
            [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ],
            [],
            [],
            [
                'REQUEST_URI' => $url,
            ]
        ));

        $this->requestListener->onKernelRequest($event);

        $this->assertEquals($expectedHasResponse, $event->hasResponse());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'invalid test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ], 'foo'),
                ],
                'testValues' => [],
                'expectedHasResponse' => true,
            ],
            'invalid test owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                ],
                'expectedHasResponse' => true,
            ],
            'valid test owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }
}
