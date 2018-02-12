<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseViewController extends Controller
{
    /**
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userManager = $this->container->get(UserManager::class);

        return [
            'user' => $userManager->getUser(),
            'is_logged_in' => $userService->isLoggedIn(),
            'public_site' => $this->container->getParameter('public_site'),
            'external_links' => $this->container->getParameter('external_links')
        ];
    }
}
