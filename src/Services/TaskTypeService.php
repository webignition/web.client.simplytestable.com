<?php

namespace App\Services;

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
     * @var UserManager
     */
    private $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

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
        $user = $this->userManager->getUser();

        $isPublicAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_PUBLIC;
        $isAuthenticatedAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_AUTHENTICATED;
        $isEarlyAccessTaskType = $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_EARLY_ACCESS;
        $isEarlyAccessUser = in_array($user->getUsername(), $this->earlyAccessUsers);

        $isUserAuthenticated = !SystemUserService::isPublicUser($user);

        if ($isPublicAccessTaskType) {
            return true;
        }

        if ($isAuthenticatedAccessTaskType && $isUserAuthenticated) {
            return true;
        }

        if ($isEarlyAccessTaskType && $isEarlyAccessUser) {
            return true;
        }

        return false;
    }
}
