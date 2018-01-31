<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller
{
    const DEFAULT_WEBSITE_SCHEME = 'http';

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    public function getUser()
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $user = $userService->getUser();

        if ($userService->isPublicUser($user)) {
            $userCookie = $this->getRequest()->cookies->get('simplytestable-user');
            if (!is_null($userCookie)) {
                $user = $userSerializerService->unserializedFromString($userCookie);
                if (is_null($user)) {
                    $user = $userService->getPublicUser();
                } else {
                    $userService->setUser($user);
                }
            }
        }

        return $userService->getUser();
    }
}
