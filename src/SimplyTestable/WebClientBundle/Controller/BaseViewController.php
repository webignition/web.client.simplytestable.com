<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseViewController extends Controller
{
    /**
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        $userService = $this->container->get('simplytestable.services.userservice');

        return [
            'user' => $userService->getUser(),
            'is_logged_in' => $userService->isLoggedIn(),
            'public_site' => $this->container->getParameter('public_site'),
            'external_links' => $this->container->getParameter('external_links')
        ];
    }
}
