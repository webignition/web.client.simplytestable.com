<?php

namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Exception\TooManyRedirectsException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Backoff\BackoffPlugin;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;

class CoreApplicationHttpClient
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';

    const OPT_TREAT_404_AS_EMPTY = 'treat-404-as-empty';
    const OPT_EXPECT_JSON_RESPONSE = 'expect-json-response';
    const OPT_DISABLE_REDIRECT = 'disable-redirect';

    const ROUTE_PARAMETER_USER_PLACEHOLDER = '{{ user }}';

    /**
     * @var CoreApplicationRouter
     */
    private $router;

    /**
     * @var HttpClient
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
        $this->httpClient->addSubscriber(BackoffPlugin::getExponentialBackoff(
            3,
            array(500, 503, 504)
        ));
    }


    /**
     * @return HttpClient
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
     * @return Response|array|mixed|null
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
     * @return Response|array|mixed|null
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
     * @return Response|array|mixed|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    private function getAsUser(User $user, $routeName, array $routeParameters = [], array $options = [])
    {
        $requestUrl = $this->createRequestUrl($routeName, $routeParameters);

        $request = $this->httpClient->createRequest('GET', $requestUrl);

        return $this->getResponse($request, $options, $user);
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param array $postData
     * @param array $options
     *
     * @return Response|array|mixed|null
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
     * @return Response|array|mixed|null
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
     * @return Response|array|mixed|null
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
        $request = $this->httpClient->createRequest('POST', $requestUrl, [], $postData);

        return $this->getResponse($request, $options, $user);
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @param User $user
     * @return Response|null
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    private function getResponse(RequestInterface $request, array $options, User $user)
    {
        if ($this->isOptionTrue(self::OPT_DISABLE_REDIRECT, $options)) {
            $request->getParams()->set('redirect.disable', true);
        }

        $this->addAuthorizationToRequest($request, $user);

        $response = $this->responseCache->get($request);

        if (empty($response)) {
            try {
                $response = $request->send();
                $this->responseCache->set($request, $response);
            } catch (TooManyRedirectsException $tooManyRedirectsException) {
                // not sure if we really need to handle these
            } catch (ClientErrorResponseException $clientErrorResponseException) {
                // 400 bad request: check for x-foo-error-code and x-foo-error message headers
                // 401: invalid auth
                // 404: often core app way of saying 'no'

                $response = $clientErrorResponseException->getResponse();
                $responseStatusCode = $response->getStatusCode();

                if (in_array($responseStatusCode, [401, 403])) {
                    if ($this->adminUser->getUsername() === $user->getUsername()) {
                        throw new InvalidAdminCredentialsException();
                    }

                    throw new InvalidCredentialsException();
                }

                if (404 === $response->getStatusCode()) {
                    if ($this->isOptionTrue(self::OPT_TREAT_404_AS_EMPTY, $options)) {
                        return null;
                    }
                }

                throw new CoreApplicationRequestException($clientErrorResponseException);
            } catch (ServerErrorResponseException $serverErrorResponseException) {
                // 500: boom
                // 503: read only
                $response = $serverErrorResponseException->getResponse();

                if (503 === $response->getStatusCode()) {
                    throw new CoreApplicationReadOnlyException();
                }

                throw new CoreApplicationRequestException($serverErrorResponseException);
            } catch (CurlException $curlException) {
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
