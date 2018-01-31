<?php

namespace SimplyTestable\WebClientBundle\Controller;

abstract class BaseViewController extends BaseController
{
    /**
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        return [
            'user' => $this->getUserService()->getUser(),
            'is_logged_in' => $this->getUserService()->isLoggedIn(),
            'public_site' => $this->container->getParameter('public_site'),
            'external_links' => $this->container->getParameter('external_links')
        ];
    }
}
