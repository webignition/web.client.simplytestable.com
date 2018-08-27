<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use webignition\SimplyTestableUserHydrator\UserHydrator;
use webignition\SimplyTestableUserInterface\UserInterface;
use webignition\SimplyTestableUserManagerInterface\UserManagerInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserManager implements UserManagerInterface
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
     * @param UserSerializer $userSerializer
     * @param SessionInterface $session
     * @param SystemUserService $systemUserService
     * @param UserHydrator $userHydrator
     */
    public function __construct(
        UserSerializer $userSerializer,
        SessionInterface $session,
        SystemUserService $systemUserService,
        UserHydrator $userHydrator
    ) {
        $this->userSerializer = $userSerializer;
        $this->session = $session;
        $this->systemUserService = $systemUserService;

        $user = $userHydrator->getUser();

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

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function isLoggedIn(): bool
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
