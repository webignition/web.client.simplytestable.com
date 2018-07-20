<?php

namespace AppBundle\Services\MailChimp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AppBundle\Exception\MailChimp\MemberExistsException;
use AppBundle\Exception\MailChimp\ResourceNotFoundException;
use AppBundle\Exception\MailChimp\UnknownException;
use AppBundle\Model\MailChimp\ApiError;
use AppBundle\Model\MailChimp\ListMembers;
use webignition\Guzzle\Middleware\HttpAuthentication\HttpAuthenticationCredentials;
use webignition\Guzzle\Middleware\HttpAuthentication\HttpAuthenticationMiddleware;

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
     * @var string
     */
    private $baseUrl;

    /**
     * @var HttpAuthenticationMiddleware
     */
    private $httpAuthenticationMiddleware;

    /**
     * @var HttpAuthenticationCredentials
     */
    private $httpAuthenticationCredentials;

    /**
     * @param string $apiKey
     * @param GuzzleClient $httpClient
     * @param HttpAuthenticationMiddleware $httpAuthenticationMiddleware
     */
    public function __construct(
        $apiKey,
        GuzzleClient $httpClient,
        HttpAuthenticationMiddleware $httpAuthenticationMiddleware
    ) {
        $this->apiKey = $apiKey;

        $apiKeyParts = explode('-', $apiKey);
        $dataCenterName = $apiKeyParts[1];

        $this->baseUrl = sprintf(self::BASE_URL, $dataCenterName);
        $this->httpClient = $httpClient;
        $this->httpAuthenticationMiddleware = $httpAuthenticationMiddleware;
        $this->httpAuthenticationCredentials = new HttpAuthenticationCredentials(
            'anystring',
            $this->apiKey,
            'api.mailchimp.com'
        );
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

        $request = new Request('GET', $this->createRequestUrl($endpointUrl));

        $response = $this->sendRequest($request);

        return new ListMembers(json_decode($response->getBody()->getContents(), true));
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

        $request = new Request(
            'POST',
            $this->createRequestUrl($endpointUrl),
            ['content-type' => 'application/json',],
            json_encode($postData)
        );

        try {
            $this->sendRequest($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $errorData = json_decode($response->getBody()->getContents(), true);

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

        $request = new Request('DELETE', $this->createRequestUrl($endpointUrl));

        try {
            $this->sendRequest($request);
        } catch (ClientException $clientErrorResponseException) {
            $response = $clientErrorResponseException->getResponse();
            $errorData = json_decode($response->getBody()->getContents(), true);

            $error = new ApiError($errorData);

            if ($error->isResourceNotFoundError()) {
                throw new ResourceNotFoundException($error);
            }

            throw new UnknownException($error);
        }
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    private function sendRequest(RequestInterface $request)
    {
        $this->httpAuthenticationMiddleware->setHttpAuthenticationCredentials($this->httpAuthenticationCredentials);
        $this->httpAuthenticationMiddleware->setIsSingleUse(true);

        return $this->httpClient->send($request);
    }

    /**
     * @param string $endpointUrl
     *
     * @return string
     */
    private function createRequestUrl($endpointUrl)
    {
        return $this->baseUrl . self::BASE_URL_API_VERSION . $endpointUrl;
    }
}
