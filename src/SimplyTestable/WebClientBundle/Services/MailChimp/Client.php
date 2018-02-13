<?php

namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\RequestInterface;
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

        $this->httpClient = new GuzzleClient(sprintf(self::BASE_URL, $dataCenterName));
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
            'fields' => 'members.email_address',
        ];

        $endpointUrl = sprintf(self::PATH_LIST_MEMBERS, $listId);
        $endpointUrl .= '?' . http_build_query($queryStringParameters);

        $request = $this->createRequest('GET', $endpointUrl);
        $response = $request->send();

        return new ListMembers(json_decode($response->getBody(), true));
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
            $request->send();
        } catch (ClientErrorResponseException $clientErrorResponseException) {
            $response = $clientErrorResponseException->getResponse();
            $errorData = json_decode($response->getBody(), true);

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
            $request->send();
        } catch (ClientErrorResponseException $clientErrorResponseException) {
            $response = $clientErrorResponseException->getResponse();
            $errorData = json_decode($response->getBody(), true);

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
        return $this->httpClient->createRequest(
            $method,
            $this->createRequestUrl($endpointUrl),
            array_merge([
                'Authorization' => 'Basic ' . base64_encode(sprintf(
                    '%s:%s',
                    'anystring',
                    $this->apiKey
                )),
            ], $headers),
            $body
        );
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
