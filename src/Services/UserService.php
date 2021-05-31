<?php

namespace App\Services;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAlreadyExistsException;
use App\Model\User\Summary as UserSummary;
use App\Model\Team\Invite;
use App\Model\Coupon;
use webignition\SimplyTestableUserModel\User;

class UserService
{
    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * @var array
     */
    private $userExistsCache = [];

    /**
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     * @param JsonResponseHandler $jsonResponseHandler
     */
    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function resetPassword($token, $password)
    {
        $this->coreApplicationHttpClient->post(
            'user_reset_password',
            [
                'token' => $token,
            ],
            [
                'password' => rawurlencode($password),
            ]
        );
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        try {
            $this->coreApplicationHttpClient->get('user_authenticate', [
                'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return false;
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return false;
        }

        return true;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $plan
     * @param Coupon|null $coupon
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws UserAlreadyExistsException
     * @throws InvalidAdminCredentialsException
     */
    public function create($email, $password, $plan, Coupon $coupon = null)
    {
        $requestData = [
            'email' => rawurlencode($email),
            'password' => rawurlencode($password),
            'plan' => $plan,
        ];

        if (!empty($coupon)) {
            $requestData['coupon'] = $coupon->getCode();
        }

        $response = $this->coreApplicationHttpClient->postAsAdmin(
            'user_create',
            [],
            $requestData,
            [
                CoreApplicationHttpClient::OPT_DISABLE_REDIRECT,
            ]
        );

        if (302 === $response->getStatusCode()) {
            throw new UserAlreadyExistsException();
        }
    }

    /**
     * @param string $token
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function activate($token)
    {
        $this->coreApplicationHttpClient->postAsAdmin('user_activate', [
            'token' => $token,
        ]);
    }

    /**
     * @param Invite $invite
     * @param string $password
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function activateAndAccept(Invite $invite, $password)
    {
        $this->coreApplicationHttpClient->post('teaminvite_activateandaccept', [], [
            'token' => $invite->getToken(),
            'password' => rawurlencode($password),
        ]);
    }
    /**
     * @param string|null $email
     *
     * @return bool
     *
     * @throws InvalidAdminCredentialsException
     */
    public function exists($email = null)
    {
        $email = empty($email)
            ? CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER
            : $email;

        if (isset($this->userExistsCache[$email])) {
            return $this->userExistsCache[$email];
        }

        $exists = false;

        try {
            $this->coreApplicationHttpClient->postAsAdmin('user_exists', [
                'email' => $email,
            ]);

            $exists = true;
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
        }

        $this->userExistsCache[$email] = $exists;

        return $exists;
    }

    /**
     * @param string|null $email
     * @return bool
     *
     * @throws InvalidAdminCredentialsException
     */
    public function isEnabled($email = null)
    {
        $email = empty($email)
            ? CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER
            : $email;

        if (!$this->exists($email)) {
            return false;
        }

        try {
            $this->coreApplicationHttpClient->getAsAdmin('user_is_enabled', [
                'email' => $email,
            ]);
        } catch (CoreApplicationRequestException $applicationRequestException) {
            return false;
        }

        return true;
    }

    /**
     * @param string|null $email
     *
     * @return string
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function getConfirmationToken($email)
    {
        $response = $this->coreApplicationHttpClient->getAsAdmin('user_get_token', [
            'email' => $email,
        ]);

        return $this->jsonResponseHandler->handle($response);
    }

    /**
     * @param User|null $user
     *
     * @return UserSummary
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getSummary(User $user = null)
    {
        $email = (empty($user))
            ? CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER
            : $user->getUsername();

        $response = $this->coreApplicationHttpClient->get('user', [
            'email' => $email,
        ]);

        $responseData = $this->jsonResponseHandler->handle($response);

        return new UserSummary($responseData);
    }
}
