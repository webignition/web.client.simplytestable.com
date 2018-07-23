<?php
namespace App\Services;

use webignition\SimplyTestableUserModel\User;

class TaskTypeService
{
    const ACCESS_LEVEL_PUBLIC = 'public';
    const ACCESS_LEVEL_AUTHENTICATED = 'authenticated';
    const ACCESS_LEVEL_EARLY_ACCESS = 'early-access';

    /**
     * @var array
     */
    private $taskTypes;

    /**
     * @var string[]
     */
    private $earlyAccessUsers;

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $isUserAuthenticated = false;

    /**
     * @param array $taskTypes
     */
    public function setTaskTypes($taskTypes)
    {
        $this->taskTypes = $taskTypes;
    }

    /**
     * @param string[] $earlyAccessUsers
     */
    public function setEarlyAccessUsers($earlyAccessUsers)
    {
        $this->earlyAccessUsers = $earlyAccessUsers;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setUserIsAuthenticated()
    {
        $this->isUserAuthenticated = true;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->taskTypes;
    }

    /**
     * @return array
     */
    public function getAvailable()
    {
        $taskTypes = [];

        foreach ($this->taskTypes as $taskTypeKey => $taskTypeDetails) {
            if ($this->isAllowedTaskType($taskTypeDetails)) {
                $taskTypes[$taskTypeKey] = $taskTypeDetails;
            }
        }

        return $taskTypes;
    }

    /**
     * @param $taskTypeDetails
     *
     * @return bool
     */
    private function isAllowedTaskType($taskTypeDetails)
    {
        $isPublicAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_PUBLIC;
        $isAuthenticatedAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_AUTHENTICATED;
        $isEarlyAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_EARLY_ACCESS;
        $isEarlyAccessUser = in_array($this->user->getUsername(), $this->earlyAccessUsers);

        if ($isPublicAccessTaskType) {
            return true;
        }

        if ($isAuthenticatedAccessTaskType && $this->isUserAuthenticated) {
            return true;
        }

        if ($isEarlyAccessTaskType && $isEarlyAccessUser) {
            return true;
        }
    }
}
