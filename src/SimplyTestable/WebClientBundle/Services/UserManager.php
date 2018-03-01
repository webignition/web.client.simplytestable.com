<?php

namespace SimplyTestable\WebClientBundle\Services;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\InvalidCipherTextException;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserManager
{
    const USER_COOKIE_KEY = 'simplytestable-user';
    const USER_COOKIE_DOMAIN = '.simplytestable.com';
    const USER_COOKIE_PATH = '/';

    const SESSION_USER_KEY = 'user';
    const ONE_YEAR_IN_SECONDS = 31536000;

    /**
     * @var UserSerializer
     */
    private $userSerializer;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SystemUserService
     */
    private $systemUserService;

    /**
     * @var User
     */
    private $user;

    /**
     * @param RequestStack $requestStack
     * @param UserSerializer $userSerializer
     * @param SessionInterface $session
     * @param SystemUserService $systemUserService
     */
    public function __construct(
        RequestStack $requestStack,
        UserSerializer $userSerializer,
        SessionInterface $session,
        SystemUserService $systemUserService
    ) {
        $this->userSerializer = $userSerializer;
        $this->session = $session;
        $this->systemUserService = $systemUserService;

        $user = null;
        $request = $requestStack->getCurrentRequest();

        if (!empty($request)) {
            if ($request->cookies->has(self::USER_COOKIE_KEY)) {
                try {
                    $user = $this->userSerializer->deserializeFromString(
                        $request->cookies->get(self::USER_COOKIE_KEY)
                    );
                } catch (InvalidCipherTextException $invalidHmacException) {
                }
            }
        }

        if (empty($user)) {
            $sessionUser = $this->session->get(self::SESSION_USER_KEY);

            if (!empty($sessionUser)) {
                try {
                    $user = $this->userSerializer->deserialize($sessionUser);
                } catch (InvalidCipherTextException $invalidHmacException) {
                }
            }
        }

        if (empty($user)) {
            $user = SystemUserService::getPublicUser();
        }

        $this->setUser($user);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $this->session->set(self::SESSION_USER_KEY, $this->userSerializer->serialize($user));
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        $user = $this->user;

        if (SystemUserService::isPublicUser($user)) {
            return false;
        }

        if ($user->equals($this->systemUserService->getAdminUser())) {
            return false;
        }

        return true;
    }

    public function clearSessionUser()
    {
        $this->session->set(self::SESSION_USER_KEY, null);
    }

    /**
     * @return Cookie
     */
    public function createUserCookie()
    {
        return new Cookie(
            self::USER_COOKIE_KEY,
            $this->userSerializer->serializeToString($this->user),
            time() + self::ONE_YEAR_IN_SECONDS,
            self::USER_COOKIE_PATH,
            self::USER_COOKIE_DOMAIN,
            false,
            true
        );
    }
}
