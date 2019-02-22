<?php

namespace App\Tests\Factory;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HttpResponseFactory
{
    public static function create(int $statusCode, array $headers = [], string $body = ''): ResponseInterface
    {
        return new Response($statusCode, $headers, $body);
    }

    public static function createJsonResponse($data, int $statusCode = 200): ResponseInterface
    {
        return self::create(
            $statusCode,
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($data)
        );
    }

    public static function createNotFoundResponse(array $headers = [], string $body = ''): ResponseInterface
    {
        return self::create(404, $headers, $body);
    }

    public static function createSuccessResponse(array $headers = [], string $body = ''): ResponseInterface
    {
        return self::create(200, $headers, $body);
    }

    public static function createUnauthorisedResponse(): ResponseInterface
    {
        return self::create(401);
    }

    public static function createForbiddenResponse(): ResponseInterface
    {
        return self::create(403);
    }

    public static function createInternalServerErrorResponse(): ResponseInterface
    {
        return self::create(500);
    }

    public static function createServiceUnavailableResponse(): ResponseInterface
    {
        return self::create(503);
    }

    public static function createBadRequestResponse(array $headers = [], string $body = ''): ResponseInterface
    {
        return self::create(400, $headers, $body);
    }

    public static function createConflictResponse(): ResponseInterface
    {
        return self::create(409);
    }

    public static function createMailChimpListMembersResponse(int $total, array $memberEmails): ResponseInterface
    {
        $memberRecords = [];

        foreach ($memberEmails as $memberEmail) {
            $memberRecords[] = [
                'email_address' => $memberEmail,
            ];
        }

        return self::createJsonResponse([
            'total_items' => $total,
            'members' => $memberRecords,
        ]);
    }

    public static function createRedirectResponse(): ResponseInterface
    {
        return self::create(302);
    }

    public static function createPostmarkResponse(int $statusCode, array $responseData): Response
    {
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($responseData)
        );
    }
}
