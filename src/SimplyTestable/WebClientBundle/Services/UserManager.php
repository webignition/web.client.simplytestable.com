<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;

class UserManager
{
    const USER_COOKIE_KEY = 'simplytestable-user';

    /**
     * @var UserSerializerService
     */
    private $userSerializer;

    /**
     * @var User
     */
    private $user;

    /**
     * @param RequestStack $requestStack
     * @param UserSerializerService $userSerializer
     */
    public function __construct(
        RequestStack $requestStack,
        UserSerializerService $userSerializer
    ) {
        $this->userSerializer = $userSerializer;

        $request = $requestStack->getCurrentRequest();

        $user = null;

        if ($request->cookies->has(self::USER_COOKIE_KEY)) {
            $serializedUser = $request->cookies->get(self::USER_COOKIE_KEY);

            $user = $this->userSerializer->unserializedFromString($serializedUser);
        }

        if (empty($user)) {
            $user = SystemUserService::getPublicUser();
        }

        $this->user = $user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
