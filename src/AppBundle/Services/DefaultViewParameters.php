<?php

namespace AppBundle\Services;

class DefaultViewParameters
{
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
     * @return array
     */
    public function getDefaultViewParameters()
    {
        return [
            'user' => $this->userManager->getUser(),
            'is_logged_in' => $this->userManager->isLoggedIn(),
        ];
    }
}
