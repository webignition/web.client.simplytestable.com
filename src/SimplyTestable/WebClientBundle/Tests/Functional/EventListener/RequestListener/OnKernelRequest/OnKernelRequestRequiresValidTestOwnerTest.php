<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OnKernelRequestRequiresValidTestOwnerTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification\UrlLimitController::indexAction';
    const CONTROLLER_ROUTE = 'view_test_partial_notification_urlimit_index';

    const WEBSITE = 'http://example.com';
    const TEST_ID = 1;

    /**
     * @dataProvider dataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param bool $expectedHasResponse
     *
     * @throws WebResourceException
     * @throws \Exception
     */
    public function testOnKernelRequest(array $httpFixtures, array $testValues, $expectedHasResponse)
    {
        $this->setHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE
        );

        $this->requestListener->onKernelRequest($event);

        $response = $event->getResponse();

        if ($expectedHasResponse) {
            $this->assertInstanceOf(Response::class, $response);
        } else {
            $this->assertNull($response);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'invalid test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse([], 'foo'),
                ],
                'testValues' => [],
                'expectedHasResponse' => true,
            ],
            'invalid test owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
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
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => self::WEBSITE,
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                ],
                'expectedHasResponse' => false,
            ],
        ];
    }

    public function testOnKernelRequestWebResourceException()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $testFactory = new TestFactory($this->container);
        $testFactory->create([
            TestFactory::KEY_WEBSITE => self::WEBSITE,
            TestFactory::KEY_TEST_ID => self::TEST_ID,
        ]);

        $request = new Request([], [], [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE
        );

        $this->setExpectedException(WebResourceException::class, 'Not Found', 404);

        $this->requestListener->onKernelRequest($event);
    }
}