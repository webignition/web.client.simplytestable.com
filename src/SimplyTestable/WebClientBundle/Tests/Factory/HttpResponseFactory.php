<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\Stream;

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
        return new Response($statusCode, $headers, Stream::factory($body));
//
//        $headersStringParts = [];
//        foreach ($headers as $key => $value) {
//            $headersStringParts[] = $key . ':' . $value;
//        }
//
//        $headersString = implode("\n", $headersStringParts);
//
//        $message = trim(sprintf(
//            "HTTP/1.1 %s\n%s\n\n%s",
//            $statusCode,
//            $headersString,
//            $body
//        ));
//
//        return Response::fromMessage($message);
    }

    /**
     * @param mixed $data
     *
     * @return ResponseInterface
     */
    public static function createJsonResponse($data)
    {
        return self::createSuccessResponse(
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
}
