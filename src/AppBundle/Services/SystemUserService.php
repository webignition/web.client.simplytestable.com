<?php

namespace AppBundle\Services;

use webignition\SimplyTestableUserModel\User;

class SystemUserService
{
    const PUBLIC_USER_USERNAME = 'public';
    const PUBLIC_USER_PASSWORD = 'public';
    const PUBLIC_USER_EMAIL = 'public@simplytestable.com';

    /**
     * @var string
     */
    private $adminUserUsername;

    /**
     * @var string
     */
    private $adminUserPassword;


    /**
     * @param string $adminUserUsername
     * @param string $adminUserPassword
     */
    public function __construct($adminUserUsername, $adminUserPassword)
    {
        $this->adminUserUsername = $adminUserUsername;
        $this->adminUserPassword = $adminUserPassword;
    }

    /**
     * @return User
     */
    public static function getPublicUser()
    {
        return new User(self::PUBLIC_USER_USERNAME, self::PUBLIC_USER_PASSWORD);
    }

    /**
     * @return User
     */
    public function getAdminUser()
    {
        return new User($this->adminUserUsername, $this->adminUserPassword);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public static function isPublicUser(User $user)
    {
        $comparatorUsername = strtolower($user->getUsername());

        return in_array($comparatorUsername, [self::PUBLIC_USER_USERNAME, self::PUBLIC_USER_EMAIL]);
    }
}
