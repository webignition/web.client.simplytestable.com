<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Tests\Factory\HttpResponseFactory;

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

        $this->test = Test::create(1, 'http://example.com/');
    }

    public function testOwnsDirectOwner()
    {
        $this->test->setUser($this->user->getUsername());

        $this->assertTrue($this->remoteTestService->owns($this->test, $this->user));
    }

    /**
     * @dataProvider ownsRemoteExceptionDataProvider
     */
    public function testOwnsRemoteException(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->owns($this->test, $this->user);
    }

    public function ownsRemoteExceptionDataProvider(): array
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

        $this->assertFalse($this->remoteTestService->owns($this->test, $this->user));
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

        $this->assertFalse($this->remoteTestService->owns($this->test, $this->user));
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

        $this->assertTrue($this->remoteTestService->owns($this->test, $this->user));
    }

    public function testOwnsInvalidRemoteTest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->assertFalse($this->remoteTestService->owns($this->test, $this->user));
    }
}
