<?php

namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\RequestInterface;
use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Model\MailChimp\ApiError;
use SimplyTestable\WebClientBundle\Model\MailChimp\ListMembers;

class Client
{
    const BASE_URL = 'https://%s.api.mailchimp.com';
    const BASE_URL_API_VERSION = '/3.0';

    const PATH_LIST_MEMBERS = '/lists/%s/members';
    const PATH_LIST_MEMBER_DELETE = '/lists/%s/members/%s';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $apiKeyParts = explode('-', $apiKey);
        $dataCenterName = $apiKeyParts[1];

        $this->httpClient = new GuzzleClient([
            'base_url' => sprintf(self::BASE_URL, $dataCenterName),
        ]);
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param string $listId
     * @param int $count
     * @param int $offset
     *
     * @return ListMembers
     */
    public function getListMembers($listId, $count, $offset)
    {
        $queryStringParameters = [
            'count' => $count,
            'offset' => $offset,
            'fields' => 'members.email_address,total_items',
        ];

        $endpointUrl = sprintf(self::PATH_LIST_MEMBERS, $listId);
        $endpointUrl .= '?' . http_build_query($queryStringParameters);

        $request = $this->createRequest('GET', $endpointUrl);

        $response = $this->getHttpClient()->send($request);

        return new ListMembers($response->json());
    }

    /**
     * @param string $listId
     * @param string $email
     *
     * @throws MemberExistsException
     * @throws UnknownException
     */
    public function addListMember($listId, $email)
    {
        $postData = [
            'email_address' => $email,
            'status' => 'subscribed',
        ];

        $endpointUrl = sprintf(self::PATH_LIST_MEMBERS, $listId);

        $request = $this->createRequest('POST', $endpointUrl, [
            'content-type' => 'application/json',
        ], json_encode($postData));

        try {
            $this->getHttpClient()->send($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $errorData = $response->json();

            $error = new ApiError($errorData);

            if ($error->isMemberExistsError()) {
                throw new MemberExistsException($error);
            }

            throw new UnknownException($error);
        }
    }

    /**
     * @param string $listId
     * @param string $email
     *
     * @throws ResourceNotFoundException
     * @throws UnknownException
     */
    public function removeListMember($listId, $email)
    {
        $endpointUrl = sprintf(self::PATH_LIST_MEMBER_DELETE, $listId, md5($email));

        $request = $this->createRequest('DELETE', $endpointUrl);

        try {
            $this->getHttpClient()->send($request);
        } catch (ClientException $clientErrorResponseException) {
            $response = $clientErrorResponseException->getResponse();
            $errorData = $response->json();

            $error = new ApiError($errorData);

            if ($error->isResourceNotFoundError()) {
                throw new ResourceNotFoundException($error);
            }

            throw new UnknownException($error);
        }
    }

    /**
     * @param string $method
     * @param string $endpointUrl
     * @param array $headers
     * @param mixed $body
     *
     * @return RequestInterface
     */
    private function createRequest($method, $endpointUrl, $headers = [], $body = null)
    {
        $request = $this->httpClient->createRequest(
            $method,
            $this->createRequestUrl($endpointUrl),
            [
                'body' => $body,
            ]
        );

        $request->setHeaders(array_merge([
            'Authorization' => 'Basic ' . base64_encode(sprintf(
                '%s:%s',
                'anystring',
                $this->apiKey
            )),
        ], $headers));

        return $request;
    }

    /**
     * @param string $endpointUrl
     *
     * @return string
     */
    private function createRequestUrl($endpointUrl)
    {
        return self::BASE_URL_API_VERSION . $endpointUrl;
    }
}
