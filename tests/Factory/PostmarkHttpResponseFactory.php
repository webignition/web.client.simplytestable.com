<?php

namespace App\Tests\Factory;

use Psr\Http\Message\ResponseInterface;

class PostmarkHttpResponseFactory
{
    public static function createResponse(int $statusCode, array $responseData): ResponseInterface
    {
        return HttpResponseFactory::createJsonResponse($responseData, $statusCode);
    }

    public static function createSuccessResponse(
        string $recipient = 'user@example.com',
        string $submittedAt = '2014-02-17T07:25:01.4178645-05:00',
        string $messageId = '0a129aee-e1cd-480d-b08d-4f48548ff48d'
    ): ResponseInterface {
        return self::createResponse(200, [
            'To' => $recipient,
            'SubmittedAt' => $submittedAt,
            'MessageId' => $messageId,
            'ErrorCode' => 0,
            'Message' => 'OK',
        ]);
    }

    public static function createErrorResponse(int $errorCode): ResponseInterface
    {
        return self::createResponse(422, [
            'ErrorCode' => $errorCode,
            'Message' => 'not relevant',
        ]);
    }
}
