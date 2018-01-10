<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;

class TaskTypeService
{
    const ACCESS_LEVEL_PUBLIC = 'public';
    const ACCESS_LEVEL_AUTHENTICATED = 'authenticated';
    const ACCESS_LEVEL_EARLY_ACCESS = 'early-access';

    private $taskTypes;
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
        if ($this->isPublicAccessTaskType($taskTypeDetails)) {
            return true;
        }

        if ($this->isAuthenticatedAccessTaskType($taskTypeDetails) && $this->isUserAuthenticated) {
            return true;
        }

        if ($this->isEarlyAccessTaskType($taskTypeDetails) && $this->isEarlyAccessUser()) {
            return true;
        }
    }

    /**
     * @param $taskTypeDetails
     *
     * @return bool
     */
    private function isPublicAccessTaskType($taskTypeDetails)
    {
        return $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_PUBLIC;
    }

    /**
     * @param $taskTypeDetails
     *
     * @return bool
     */
    private function isAuthenticatedAccessTaskType($taskTypeDetails)
    {
        return $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_AUTHENTICATED;
    }


    /**
     * @param $taskTypeDetails
     *
     * @return bool
     */
    private function isEarlyAccessTaskType($taskTypeDetails)
    {
        return $taskTypeDetails['access-level'] === self::ACCESS_LEVEL_EARLY_ACCESS;
    }


    /**
     * @return boolean
     */
    private function isEarlyAccessUser()
    {
        return in_array($this->user->getUsername(), $this->earlyAccessUsers);
    }
}
