<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Tests\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceOwnsTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->test = new Test();
        $this->test->setTestId(1);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($this->test);
    }

    public function testOwnsDirectOwner()
    {
        $this->test->setUser($this->user->getUsername());

        $this->assertTrue($this->remoteTestService->owns($this->user));
    }

    /**
     * @dataProvider ownsRemoteExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     */
    public function testOwnsRemoteException(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->owns($this->user);
    }

    /**
     * @return array
     */
    public function ownsRemoteExceptionDataProvider()
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();

        return [
            'http 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
        ];
    }

    public function testOwnsRemoteHttp403()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->assertFalse($this->remoteTestService->owns($this->user));
    }

    public function testOwnsOwnersDoesNotContain()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'owners' => [
                    'foo@example.com',
                    'bar@example.com',
                ],
            ]),
        ]);

        $this->assertFalse($this->remoteTestService->owns($this->user));
    }

    public function testOwnsOwnersContains()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'owners' => [
                    $this->user->getUsername(),
                    'bar@example.com',
                ],
            ]),
        ]);

        $this->assertTrue($this->remoteTestService->owns($this->user));
    }
}