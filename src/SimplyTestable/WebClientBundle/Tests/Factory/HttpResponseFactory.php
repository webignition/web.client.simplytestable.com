<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Guzzle\Http\Message\Response;

class HttpResponseFactory
{
    /**
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     *
     * @return Response
     */
    public static function create($statusCode, $headers = [], $body = '')
    {
        $headersStringParts = [];
        foreach ($headers as $key => $value) {
            $headersStringParts[] = $key . ':' . $value;
        }

        $headersString = implode("\n", $headersStringParts);

        $message = trim(sprintf(
            "HTTP/1.1 %s\n%s\n\n%s",
            $statusCode,
            $headersString,
            $body
        ));

        return Response::fromMessage($message);
    }

    /**
     * @param mixed $data
     *
     * @return Response
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
     * @return Response
     */
    public static function createNotFoundResponse()
    {
        return self::create(404);
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return Response
     */
    public static function createSuccessResponse($headers = [], $body = '')
    {
        return self::create(200, $headers, $body);
    }

    /**
     * @return Response
     */
    public static function createUnauthorisedResponse()
    {
        return self::create(401);
    }


    /**
     * @return Response
     */
    public static function createForbiddenResponse()
    {
        return self::create(403);
    }

    /**
     * @return Response
     */
    public static function createInternalServerErrorResponse()
    {
        return self::create(500);
    }

    /**
     * @return Response
     */
    public static function createServiceUnavailableResponse()
    {
        return self::create(503);
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return Response
     */
    public static function createBadRequestResponse(array $headers = [], $body = '')
    {
        return self::create(400, $headers, $body);
    }

    /**
     * @return Response
     */
    public static function createConflictResponse()
    {
        return self::create(409);
    }

    /**
     * @param int $total
     * @param string[] $memberEmails
     *
     * @return Response
     */
    public static function createMailChimpListMembersResponse($total, $memberEmails)
    {
        $memberRecords = [];

        foreach ($memberEmails as $memberEmail) {
            $memberRecords[] = [
                'email' => $memberEmail,
            ];
        }

        return self::createJsonResponse([
            'total' => $total,
            'data' => $memberRecords,
        ]);
    }

    /**
     * @return Response
     */
    public static function createRedirectResponse()
    {
        return self::create(302);
    }
}
