<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserManager
{
    const USER_COOKIE_KEY = 'simplytestable-user';
    const SESSION_USER_KEY = 'user';

    /**
     * @var UserSerializerService
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
     * @param UserSerializerService $userSerializer
     * @param SessionInterface $session
     * @param SystemUserService $systemUserService
     */
    public function __construct(
        RequestStack $requestStack,
        UserSerializerService $userSerializer,
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
                $user = $this->userSerializer->unserializedFromString(
                    $request->cookies->get(self::USER_COOKIE_KEY)
                );
            }
        }

        if (empty($user)) {
            $sessionUser = $this->session->get(self::SESSION_USER_KEY);

            if (!empty($sessionUser)) {
                $user = $this->userSerializer->unserialize($sessionUser);
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
}
