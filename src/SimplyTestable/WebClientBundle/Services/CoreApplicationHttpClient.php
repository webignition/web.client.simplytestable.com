<?php

namespace SimplyTestable\WebClientBundle\Services;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use GuzzleHttp\Subscriber\Retry\RetrySubscriber as HttpRetrySubscriber;
use webignition\GuzzleHttp\Exception\CurlException\Factory as CurlExceptionFactory;

class CoreApplicationHttpClient
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';

    const OPT_DISABLE_REDIRECT = 'disable-redirect';

    const ROUTE_PARAMETER_USER_PLACEHOLDER = '{{ user }}';

    /**
     * @var CoreApplicationRouter
     */
    private $router;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var User
     */
    private $adminUser;

    /**
     * @var CoreApplicationResponseCache
     */
    private $responseCache;

    /**
     * @var User
     */
    private $user;

    /**
     * @param CoreApplicationRouter $coreApplicationRouter
     * @param CoreApplicationResponseCache $coreApplicationResponseCache
     * @param SystemUserService $systemUserService
     */
    public function __construct(
        CoreApplicationRouter $coreApplicationRouter,
        CoreApplicationResponseCache $coreApplicationResponseCache,
        SystemUserService $systemUserService
    ) {
        $this->router = $coreApplicationRouter;
        $this->responseCache = $coreApplicationResponseCache;

        $this->adminUser = $systemUserService->getAdminUser();

        $this->httpClient = new HttpClient();

        $filter = HttpRetrySubscriber::createChainFilter([
            // Does early filter to force non-idempotent methods to NOT be retried.
            HttpRetrySubscriber::createIdempotentFilter(),
            // Retry curl-level errors
            HttpRetrySubscriber::createCurlFilter(),
            // Performs the last check, returning ``true`` or ``false`` based on
            // if the response received a 500 or 503 status code.
            HttpRetrySubscriber::createStatusFilter([500, 503])
        ]);

        $this->httpClient->getEmitter()->attach(new HttpRetrySubscriber(['filter' => $filter]));
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function get($routeName, array $routeParameters = [], array $options = [])
    {
        $response = null;

        try {
            $response = $this->getAsUser($this->user, $routeName, $routeParameters, $options);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            // Not a write request, can't happen
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            // Not an admin request, can't happen
        }

        return $response;
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function getAsAdmin($routeName, array $routeParameters = [], array $options = [])
    {
        $response = null;

        try {
            $response = $this->getAsUser($this->adminUser, $routeName, $routeParameters, $options);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            // Not a write request, can't happen
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Not a regular user request, can't happen
        }

        return $response;
    }

    /**
     * @param User $user
     * @param string $routeName
     * @param array $routeParameters
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    private function getAsUser(User $user, $routeName, array $routeParameters = [], array $options = [])
    {
        $requestUrl = $this->createRequestUrl($routeName, $routeParameters);

        $request = $this->httpClient->createRequest('GET', $requestUrl, $this->createRequestOptions($options));

        return $this->getResponse($request, $options, $user);
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param array $postData
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function post($routeName, array $routeParameters = [], array $postData = [], array $options = [])
    {
        $response = null;

        try {
            $response = $this->postAsUser($this->user, $routeName, $routeParameters, $postData, $options);
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            // Not an admin request, can't happen
        }

        return $response;
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param array $postData
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function postAsAdmin($routeName, array $routeParameters = [], array $postData = [], array $options = [])
    {
        $response = null;

        try {
            $response = $this->postAsUser($this->adminUser, $routeName, $routeParameters, $postData, $options);
        } catch (InvalidCredentialsException $invalidAdminCredentialsException) {
            // Not a regular user request, can't happen
        }

        return $response;
    }

    /**
     * @param User $user
     * @param string $routeName
     * @param array $routeParameters
     * @param array $postData
     * @param array $options
     *
     * @return ResponseInterface|array|mixed|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    private function postAsUser(
        User $user,
        $routeName,
        array $routeParameters = [],
        array $postData = [],
        array $options = []
    ) {
        $requestUrl = $this->createRequestUrl($routeName, $routeParameters);
        $request = $this->httpClient->createRequest(
            'POST',
            $requestUrl,
            $this->createRequestOptions($options, $postData)
        );

        return $this->getResponse($request, $options, $user);
    }

    /**
     * @param array $options
     * @param array|null $requestBody
     *
     * @return array
     */
    private function createRequestOptions(array $options, $requestBody = null)
    {
        $requestOptions = [];

        if (in_array(self::OPT_DISABLE_REDIRECT, $options)) {
            $requestOptions['allow_redirects'] = false;
        }

        if (!empty($requestBody)) {
            $requestOptions['body'] = $requestBody;
        }

        return $requestOptions;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @param User $user
     * @return ResponseInterface|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    private function getResponse(RequestInterface $request, array $options, User $user)
    {
        $this->addAuthorizationToRequest($request, $user);

        $response = $this->responseCache->get($request);

        if (empty($response)) {
            try {
                $response = $this->getHttpClient()->send($request);
                $response = $this->responseCache->set($request, $response);
            } catch (TooManyRedirectsException $tooManyRedirectsException) {
                // not sure if we really need to handle these
            } catch (ClientException $clientException) {
                // 400 bad request: check for x-foo-error-code and x-foo-error message headers
                // 401: invalid auth
                // 404: often core app way of saying 'no'
                $response = $clientException->getResponse();
                $responseStatusCode = $response->getStatusCode();

                if (in_array($responseStatusCode, [401, 403])) {
                    if ($this->adminUser->getUsername() === $user->getUsername()) {
                        throw new InvalidAdminCredentialsException();
                    }

                    throw new InvalidCredentialsException();
                }

                throw new CoreApplicationRequestException($clientException);
            } catch (ServerException $serverException) {
                $response = $serverException->getResponse();
                $responseStatusCode = $response->getStatusCode();

                if (503 === $responseStatusCode) {
                    throw new CoreApplicationReadOnlyException();
                }

                throw new CoreApplicationRequestException($serverException);
            } catch (ConnectException $connectException) {
                $curlException = CurlExceptionFactory::fromConnectException($connectException);

                throw new CoreApplicationRequestException($curlException);
            }
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @param User $user
     */
    private function addAuthorizationToRequest(RequestInterface $request, User $user)
    {
        $request->addHeaders([
            'Authorization' => 'Basic ' . base64_encode(sprintf(
                '%s:%s',
                $user->getUsername(),
                $user->getPassword()
            ))
        ]);
    }

    /**
     * @param string $name
     * @param array $options
     *
     * @return bool
     */
    private function isOptionTrue($name, array $options)
    {
        return isset($options[$name]) && $options[$name];
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return string
     */
    private function createRequestUrl($routeName, $routeParameters)
    {
        foreach ($routeParameters as $key => $value) {
            if (self::ROUTE_PARAMETER_USER_PLACEHOLDER === $value) {
                $routeParameters[$key] = $this->user->getUsername();
            }
        }

        return $this->router->generate($routeName, $routeParameters);
    }
}
