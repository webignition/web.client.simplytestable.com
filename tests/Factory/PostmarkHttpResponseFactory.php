<?php

namespace App\Tests\Factory;

use Psr\Http\Message\ResponseInterface;

class PostmarkHttpResponseFactory
{
    /**
     * @param $statusCode
     * @param array $responseData
     *
     * @return ResponseInterface
     */
    public static function createResponse($statusCode, array $responseData)
    {
        return HttpResponseFactory::createJsonResponse($responseData, $statusCode);
    }

    /**
     * @param string $recipient
     * @param string $submittedAt
     * @param string $messageId
     *
     * @return ResponseInterface
     */
    public static function createSuccessResponse(
        $recipient = 'user@example.com',
        $submittedAt = '2014-02-17T07:25:01.4178645-05:00',
        $messageId = '0a129aee-e1cd-480d-b08d-4f48548ff48d'
    ) {
        return self::createResponse(200, [
            'To' => $recipient,
            'SubmittedAt' => $submittedAt,
            'MessageId' => $messageId,
            'ErrorCode' => 0,
            'Message' => 'OK',
        ]);
    }

    /**
     * @param int $errorCode
     *
     * @return ResponseInterface
     */
    public static function createErrorResponse($errorCode)
    {
        return self::createResponse(422, [
            'ErrorCode' => $errorCode,
            'Message' => 'not relevant',
        ]);
    }
}
