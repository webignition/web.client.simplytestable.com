<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use Symfony\Component\HttpFoundation\Session\Session;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\Coupon;

class UserService extends CoreApplicationService
{
    const USER_COOKIE_KEY = 'simplytestable-user';

    const PUBLIC_USER_USERNAME = 'public';
    const PUBLIC_USER_PASSWORD = 'public';
    const PUBLIC_USER_EMAIL = 'public@simplytestable.com';

    /**
     * @var UserSummary[]
     */
    private $summaries = array();

    /**
     * @var string
     */
    private $adminUserUsername;

    /**
     * @var string
     */
    private $adminUserPassword;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserSerializerService
     */
    private $userSerializerService;

    /**
     * @var array
     */
    private $existsResultCache = [];

    /**
     * @var array
     */
    private $enabledResultsCache = [];

    /**
     * @var array
     */
    private $confirmationTokenCache = [];

    /**
     * @var HttpClientService
     */
    private $httpClientService;

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     * @param string $adminUserUsername
     * @param string $adminUserPassword
     * @param Session $session
     * @param UserSerializerService $userSerializerService
     */
    public function __construct(
        $parameters,
        WebResourceService $webResourceService,
        $adminUserUsername,
        $adminUserPassword,
        Session $session,
        UserSerializerService $userSerializerService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->adminUserUsername = $adminUserUsername;
        $this->adminUserPassword = $adminUserPassword;
        $this->session = $session;
        $this->userSerializerService = $userSerializerService;

        $this->httpClientService = $webResourceService->getHttpClientService();
    }

    /**
     * @return User
     */
    public function getPublicUser()
    {
        return new User(static::PUBLIC_USER_USERNAME, static::PUBLIC_USER_PASSWORD);
    }

    /**
     * @return User
     */
    public function getAdminUser()
    {
        $user = new User();
        $user->setUsername($this->adminUserUsername);
        $user->setPassword($this->adminUserPassword);
        return $user;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isPublicUser(User $user)
    {
        $comparatorUser = new User();
        $comparatorUser->setUsername(strtolower($user->getUsername()));

        if ($comparatorUser->getUsername() == 'public@simplytestable.com') {
            return true;
        }

        return $this->getPublicUser()->equals($comparatorUser);
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        $user = $this->getUser();

        if ($this->isPublicUser($user)) {
            return false;
        }

        if ($user->equals($this->getAdminUser())) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool|int
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function resetPassword($token, $password)
    {
        $request = $this->httpClientService->postRequest(
            $this->getUrl('user_reset_password', ['token' => $token]),
            null,
            [
                'password' => rawurlencode($password)
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $request->send();
        } catch (BadResponseException $badResponseException) {
            if ($badResponseException->getResponse()->getStatusCode() == 401) {
                throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
            }

            return $badResponseException->getResponse()->getStatusCode();
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }

        return true;
    }

    /**
     * @param string $password
     *
     * @return bool|int
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function resetLoggedInUserPassword($password)
    {
        $token = $this->getConfirmationToken($this->getUser()->getUsername());

        return $this->resetPassword($token, $password);
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $user = $this->getUser();

        $request = $this->httpClientService->getRequest($this->getUrl('user_authenticate', [
            'email' => $user->getUsername(),
            'password' => $user->getPassword()
        ]));

        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        }

        return $response->isSuccessful();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $plan
     * @param Coupon|null $coupon
     *
     * @return bool|int|null
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function create($email, $password, $plan, Coupon $coupon = null)
    {
        $requestData = [
            'email' => rawurlencode($email),
            'password' => rawurlencode($password),
            'plan' => $plan
        ];

        if (!is_null($coupon)) {
            $requestData['coupon'] = $coupon->getCode();
        }

        $request = $this->httpClientService->postRequest(
            $this->getUrl('user_create'),
            null,
            $requestData
        );

        $this->addAuthorisationToRequest($request);
        $request->getParams()->set('redirect.disable', true);

        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (BadResponseException $badResponseException) {
            if ($badResponseException->getResponse()->getStatusCode() == 401) {
                throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
            }

            return $badResponseException->getResponse()->getStatusCode();
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }
    }

    /**
     * @param string $token
     *
     * @return bool|int
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function activate($token)
    {
        $this->setUser($this->getAdminUser());

        $request = $this->httpClientService->postRequest(
            $this->getUrl('user_activate', ['token' => $token])
        );

        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }

        $this->setUser($this->getPublicUser());

        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }

        if ($response->getStatusCode() == 400) {
            return false;
        }

        return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
    }

    /**
     * @param Invite $invite
     * @param string $password
     *
     * @return bool|int
     */
    public function activateAndAccept(Invite $invite, $password)
    {
        $request = $this->httpClientService->postRequest(
            $this->getUrl('teaminvite_activateandaccept'),
            null,
            [
                'token' => $invite->getToken(),
                'password' => rawurlencode($password)
            ]
        );

        try {
            $request->send();
        } catch (BadResponseException $badResponseException) {
            return $badResponseException->getResponse()->getStatusCode();
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }

        return true;
    }

    /**
     * @param string $email
     * @return bool|null
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function exists($email = null)
    {
        $email = (is_null($email)) ? $this->getUser()->getUsername() : $email;

        if (!isset($this->existsResultCache[$email])) {
            $requestUrl = $this->getUrl('user_exists', [
                'email' => $email
            ]);

            $existsResult = $this->getAdminBooleanResponse($this->httpClientService->postRequest($requestUrl));

            $this->existsResultCache[$email] = $existsResult;
        }

        return $this->existsResultCache[$email];
    }

    /**
     * @param null|string $email
     * @return bool
     */
    public function hasInvites($email = null) {
        if (!$this->hasUser()) {
            return false;
        }

        $email = (is_null($email)) ? $this->getUser()->getUsername() : $email;

        return $this->getAdminBooleanResponse(
            $this->httpClientService->postRequest(
                $this->getUrl('user_hasinvites', ['email_canonical' => $email])
            )
        );

    }


    /**
     *
     * @param \Guzzle\Http\Message\Request $request
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    private function getAdminBooleanResponse(\Guzzle\Http\Message\Request $request) {
        return $this->getAdminResponse($request)->getStatusCode() === 200;
    }


    /**
     *
     * @param \Guzzle\Http\Message\Request $request
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    protected function getAdminResponse(\Guzzle\Http\Message\Request $request) {
        $currentUser = $this->getUser();

        $this->setUser($this->getAdminUser());
        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        }

        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }

        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }

        if (is_null($response)) {
            return null;
        }

        return $response;
    }


    /**
     * @param null $email
     * @return bool|null
     * @throws CoreApplicationAdminRequestException
     */
    public function isEnabled($email = null) {
        if (!$this->exists($email)) {
            return false;
        }

        $email = (is_null($email)) ? $this->getUser()->getUsername() : $email;

        if (!isset($this->enabledResultsCache[$email])) {
            $existsResult = $this->getAdminBooleanResponse($this->httpClientService->postRequest($this->getUrl('user_is_enabled', array(
                'email' => $email
            ))));
            if (is_null($existsResult)) {
                return null;
            }

            $this->enabledResultsCache[$email] = $existsResult;
        }

        return $this->enabledResultsCache[$email];
    }


    /**
     *
     * @param string $email
     * @return string
     * @throws CoreApplicationAdminRequestException
     */
    public function getConfirmationToken($email) {
        if (!isset($this->confirmationTokenCache[$email])) {
            $this->confirmationTokenCache[$email] = json_decode($this->getAdminResponse($this->httpClientService->getRequest($this->getUrl('user_get_token', array(
                'email' => $email
            ))))->getBody());
        }

        return $this->confirmationTokenCache[$email];
    }


    /**
     *
     * @param User $user
     */
    public function setUser(User $user) {
        $this->session->set('user', $this->userSerializerService->serialize($user));
        parent::setUser($user);
    }


    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null
     */
    public function setUserFromRequest(\Symfony\Component\HttpFoundation\Request $request) {
        if (!$request->cookies->has(self::USER_COOKIE_KEY)) {
            $this->setUser($this->getPublicUser());
            return;
        }

        $user = $this->userSerializerService->unserializedFromString($request->cookies->get(self::USER_COOKIE_KEY));

        if (is_null($user)) {
            return;
        }

        $this->setUser($user);
    }


    /**
     *
     * @return User
     */
    public function getUser() {
        if (is_null($this->session->get('user'))) {
            $this->setUser($this->getPublicUser());
        }

        $user = $this->userSerializerService->unserialize($this->session->get('user'));

        if ($this->isPublicUser($user)) {
            $user = $this->getPublicUser();
        }

        parent::setUser($user);

        return parent::getUser();
    }


    public function clearUser() {
        $this->session->set('user', null);
    }


    /**
     * @param User $user
     * @return UserSummary
     * @throws CurlException
     */
    public function getSummary(User $user = null) {
        if (is_null($user)) {
            $user = $this->getUser();
        }

        if (!isset($this->summaries[$user->getUsername()])) {
            $request = $this->httpClientService->getRequest(
                $this->getUrl('user', array(
                    'email' => $user->getUsername()
                ))
            );

            $this->addAuthorisationToRequest($request);

            try {
                $this->summaries[$user->getUsername()] = new UserSummary($this->webResourceService->get($request)->getContentObject());
            } catch (CurlException $curlException) {
                throw $curlException;
            }
        }

        return $this->summaries[$user->getUsername()];
    }


    /**
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return boolean
     */
    protected function httpResponseHasStripeError(\Guzzle\Http\Message\Response $response) {
        return count($this->getStripeErrorValuesFromHttpResponse($response)) > 0;
    }


    /**
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \SimplyTestable\WebClientBundle\Exception\UserAccountCardException
     */
    protected function getUserAccountCardExceptionFromHttpResponse(\Guzzle\Http\Message\Response $response) {
        $stripeErrorValues = $this->getStripeErrorValuesFromHttpResponse($response);

        $message = (isset($stripeErrorValues['message'])) ? $stripeErrorValues['message'] : '';
        $param = (isset($stripeErrorValues['param'])) ? $stripeErrorValues['param'] : '';
        $code = (isset($stripeErrorValues['code'])) ? $stripeErrorValues['code'] : '';

        return new UserAccountCardException($message, $param, $code);
    }


    /**
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return array
     */
    private function getStripeErrorValuesFromHttpResponse(\Guzzle\Http\Message\Response $response) {
        $stripeErrorKeys = array('message', 'param', 'code');
        $stripeErrorValues = array();

        foreach ($stripeErrorKeys as $stripeErrorKeySuffix) {
            $stripeErrorKey = 'x-stripe-error-' . $stripeErrorKeySuffix;

            if ($response->hasHeader($stripeErrorKey)) {
                $errorHeaderValues = $response->getHeader($stripeErrorKey)->toArray();

                if (count($errorHeaderValues)) {
                    $stripeErrorValues[$stripeErrorKeySuffix] = $errorHeaderValues[0];
                }
            }
        }

        return $stripeErrorValues;
    }


}