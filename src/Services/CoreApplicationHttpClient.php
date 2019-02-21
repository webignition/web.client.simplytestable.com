<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidCredentialsException;
use webignition\Guzzle\Middleware\HttpAuthentication\AuthorizationType;
use webignition\Guzzle\Middleware\HttpAuthentication\CredentialsFactory;
use webignition\Guzzle\Middleware\HttpAuthentication\HttpAuthenticationMiddleware;
use webignition\GuzzleHttp\Exception\CurlException\Factory as CurlExceptionFactory;
use webignition\SimplyTestableUserModel\User;

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
     * @var UserManager
     */
    private $userManager;

    /**
     * @var HttpAuthenticationMiddleware
     */
    private $httpAuthenticationMiddleware;

    /**
     * @param CoreApplicationRouter $coreApplicationRouter
     * @param CoreApplicationResponseCache $coreApplicationResponseCache
     * @param SystemUserService $systemUserService
     * @param UserManager $userManager
     * @param HttpClient $httpClient
     * @param HttpAuthenticationMiddleware $httpAuthenticationMiddleware
     */
    public function __construct(
        CoreApplicationRouter $coreApplicationRouter,
        CoreApplicationResponseCache $coreApplicationResponseCache,
        SystemUserService $systemUserService,
        UserManager $userManager,
        HttpClient $httpClient,
        HttpAuthenticationMiddleware $httpAuthenticationMiddleware
    ) {
        $this->router = $coreApplicationRouter;
        $this->responseCache = $coreApplicationResponseCache;
        $this->userManager = $userManager;

        $this->adminUser = $systemUserService->getAdminUser();

        $this->httpClient = $httpClient;
        $this->httpAuthenticationMiddleware = $httpAuthenticationMiddleware;
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
        $user = $this->userManager->getUser();

        try {
            $response = $this->getAsUser($user, $routeName, $routeParameters, $options);
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
        $request = new Request('GET', $requestUrl);

        return $this->getResponse($request, $user, $this->createRequestOptions($options));
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
        $user = $this->userManager->getUser();

        try {
            $response = $this->postAsUser($user, $routeName, $routeParameters, $postData, $options);
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
        $request = new Request(
            'POST',
            $requestUrl,
            ['content-type' => 'application/x-www-form-urlencoded'],
            http_build_query($postData, '', '&')
        );

        return $this->getResponse($request, $user, $this->createRequestOptions($options));
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function createRequestOptions(array $options)
    {
        $requestOptions = [];

        if (in_array(self::OPT_DISABLE_REDIRECT, $options)) {
            $requestOptions['allow_redirects'] = false;
        }

        return $requestOptions;
    }

    /**
     * @param RequestInterface $request
     * @param User $user
     * @param array $requestOptions
     *
     * @return ResponseInterface|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getResponse(RequestInterface $request, User $user, array $requestOptions)
    {
        $credentials = CredentialsFactory::createBasicCredentials($user->getUsername(), $user->getPassword());

        $this->httpAuthenticationMiddleware->setType(AuthorizationType::BASIC);
        $this->httpAuthenticationMiddleware->setHost($this->router->getHost());
        $this->httpAuthenticationMiddleware->setCredentials($credentials);

        $response = $this->responseCache->get($request);

        if (empty($response)) {
            try {
                $response = $this->httpClient->send($request, $requestOptions);
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
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return string
     */
    private function createRequestUrl($routeName, $routeParameters)
    {
        $user = $this->userManager->getUser();

        foreach ($routeParameters as $key => $value) {
            if (self::ROUTE_PARAMETER_USER_PLACEHOLDER === $value) {
                $routeParameters[$key] = $user->getUsername();
            }
        }

        return $this->router->generate($routeName, $routeParameters);
    }
}
