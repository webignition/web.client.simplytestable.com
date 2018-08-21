<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\InvalidCipherTextException;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserHydrator
{
    const USER_COOKIE_KEY = 'simplytestable-user';
    const SESSION_USER_KEY = 'user';

    /**
     * @var UserSerializer
     */
    private $userSerializer;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var User
     */
    private $user;

    public function __construct(RequestStack $requestStack, UserSerializer $userSerializer, SessionInterface $session)
    {
        $this->userSerializer = $userSerializer;
        $this->session = $session;

        $user = null;
        $request = $requestStack->getCurrentRequest();

        if (!empty($request) && $request->cookies->has(self::USER_COOKIE_KEY)) {
            $user = $this->hydrateUserFromRequestCookie($request);
        }

        if (empty($user) && $session->has(self::SESSION_USER_KEY)) {
            $user = $this->hydrateUserFromSession();
        }

        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Request $request
     *
     * @return null|User
     */
    private function hydrateUserFromRequestCookie(Request $request)
    {
        $user = null;

        try {
            $user = $this->userSerializer->deserializeFromString(
                $request->cookies->get(self::USER_COOKIE_KEY)
            );
        } catch (InvalidCipherTextException $invalidHmacException) {
        }

        return $user;
    }

    /**
     * @return null|User
     */
    private function hydrateUserFromSession()
    {
        $sessionUser = $this->session->get(self::SESSION_USER_KEY);

        if (empty($sessionUser)) {
            return null;
        }

        $user = null;

        try {
            $user = $this->userSerializer->deserialize($sessionUser);
        } catch (InvalidCipherTextException $invalidHmacException) {
        }

        return $user;
    }
}
