<?php

namespace App\Services;

use webignition\SimplyTestableUserInterface\UserInterface;
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

    public static function getPublicUser(): UserInterface
    {
        return new User(self::PUBLIC_USER_USERNAME, self::PUBLIC_USER_PASSWORD);
    }

    public function getAdminUser(): UserInterface
    {
        return new User($this->adminUserUsername, $this->adminUserPassword);
    }

    public static function isPublicUser(UserInterface $user): bool
    {
        $comparatorUsername = strtolower($user->getUsername());

        return in_array($comparatorUsername, [self::PUBLIC_USER_USERNAME, self::PUBLIC_USER_EMAIL]);
    }
}
