<?php

namespace Tests\WebClientBundle\Factory;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HttpResponseFactory
{
    /**
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public static function create($statusCode, $headers = [], $body = '')
    {
        return new Response($statusCode, $headers, $body);
    }

    /**
     * @param mixed $data
     * @param int $statusCode
     *
     * @return ResponseInterface
     */
    public static function createJsonResponse($data, $statusCode = 200)
    {
        return self::create(
            $statusCode,
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($data)
        );
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public static function createNotFoundResponse($headers = [], $body = '')
    {
        return self::create(404, $headers, $body);
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public static function createSuccessResponse($headers = [], $body = '')
    {
        return self::create(200, $headers, $body);
    }

    /**
     * @return ResponseInterface
     */
    public static function createUnauthorisedResponse()
    {
        return self::create(401);
    }


    /**
     * @return ResponseInterface
     */
    public static function createForbiddenResponse()
    {
        return self::create(403);
    }

    /**
     * @return ResponseInterface
     */
    public static function createInternalServerErrorResponse()
    {
        return self::create(500);
    }

    /**
     * @return ResponseInterface
     */
    public static function createServiceUnavailableResponse()
    {
        return self::create(503);
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public static function createBadRequestResponse(array $headers = [], $body = '')
    {
        return self::create(400, $headers, $body);
    }

    /**
     * @return ResponseInterface
     */
    public static function createConflictResponse()
    {
        return self::create(409);
    }

    /**
     * @param int $total
     * @param string[] $memberEmails
     *
     * @return ResponseInterface
     */
    public static function createMailChimpListMembersResponse($total, $memberEmails)
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

    /**
     * @return ResponseInterface
     */
    public static function createRedirectResponse()
    {
        return self::create(302);
    }

    /**
     * @param int $statusCode
     * @param array $responseData
     *
     * @return Response
     */
    public static function createPostmarkResponse($statusCode, array $responseData)
    {
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($responseData)
        );
    }
}
