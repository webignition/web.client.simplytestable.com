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
    public function getUser() {
        $user = $this->getUserService()->getUser();

        if ($this->getUserService()->isPublicUser($user)) {
            $userCookie = $this->getRequest()->cookies->get('simplytestable-user');
            if (!is_null($userCookie)) {
                $user = $this->getUserSerializerService()->unserializedFromString($userCookie);
                if (is_null($user)) {
                    $user = $this->getUserService()->getPublicUser();
                } else {
                    $this->getUserService()->setUser($user);
                }
            }
        }

        return $this->getUserService()->getUser();
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    protected function getUserService() {
        return $this->get('simplytestable.services.userservice');
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserSerializerService
     */
    protected function getUserSerializerService() {
        return $this->container->get('simplytestable.services.userserializerservice');
    }
}
