<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class RemoteTestServiceIsAuthorisedTest extends AbstractRemoteTestServiceTest
{
    const TEST_ID = 1;

    /**
     * @dataProvider isAuthorisedExceptionCasesDataProvider
     */
    public function testIsAuthorisedExceptionCases(ResponseInterface $httpResponse)
    {
        $this->httpMockHandler->appendFixtures([$httpResponse]);

        $this->assertFalse($this->remoteTestService->isAuthorised(self::TEST_ID));
    }

    public function isAuthorisedExceptionCasesDataProvider(): array
    {
        return [
            'http 404' => [
                'httpResponse' => new Response(404),
            ],
            'http 401' => [
                'httpResponse' => new Response(401),
            ],
            'invalid response content type' => [
                'httpResponse' => new Response(200, ['content-type' => 'text/plain']),
            ],
        ];
    }

    /**
     * @dataProvider isAuthorisedSuccessDataProvider
     */
    public function testIsAuthorisedSuccess(bool $isAuthorised)
    {
        $this->httpMockHandler->appendFixtures([
           new Response(200, ['content-type' => 'application/json'], json_encode($isAuthorised))
        ]);

        $this->assertEquals($isAuthorised, $this->remoteTestService->isAuthorised(self::TEST_ID));
    }

    public function isAuthorisedSuccessDataProvider(): array
    {
        return [
            'not authorised' => [
                'isAuthorised' => false,
            ],
            'is authorised' => [
                'isAuthorised' => true,
            ],
        ];
    }
}
