<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class TaskServiceRetrieveRemoteTaskIdsTest extends AbstractTaskServiceTest
{
    const TEST_ID = 1;

    public function testGetRemoteTaskIdsHttpClientException()
    {
        $this->httpMockHandler->appendFixtures([
            new Response(404),
        ]);

        $this->expectException(CoreApplicationRequestException::class);

        $this->taskService->retrieveRemoteTaskIds(self::TEST_ID);
    }

    public function testGetRemoteTaskInvalidHttpResponse()
    {
        $this->httpMockHandler->appendFixtures([
            new Response(200, ['content-type' => 'text/plain']),
        ]);

        $this->expectException(InvalidContentTypeException::class);

        $this->taskService->retrieveRemoteTaskIds(self::TEST_ID);
    }

    public function testGetRemoteTaskInvalidCredentials()
    {
        $this->httpMockHandler->appendFixtures([
            new Response(401),
        ]);

        $this->expectException(InvalidCredentialsException::class);

        $this->taskService->retrieveRemoteTaskIds(self::TEST_ID);
    }

    /**
     * @dataProvider getRemoteTaskIdsSuccessDataProvider
     */
    public function testGetRemoteTaskIdsSuccess(ResponseInterface $httpResponse, array $expectedTaskIds)
    {
        $this->httpMockHandler->appendFixtures([$httpResponse]);

        $this->assertEquals($expectedTaskIds, $this->taskService->retrieveRemoteTaskIds(self::TEST_ID));
    }

    public function getRemoteTaskIdsSuccessDataProvider(): array
    {
        return [
            'no task ids' => [
                'httpResponse' => new Response(200, ['content-type' => 'application/json'], json_encode([])),
                'expectedTaskIds' => [],
            ],
            'has task ids' => [
                'httpResponse' => new Response(200, ['content-type' => 'application/json'], json_encode([
                    1, 2, 3, 4, 5
                ])),
                'expectedTaskIds' => [1, 2, 3, 4, 5],
            ],
        ];
    }
}
