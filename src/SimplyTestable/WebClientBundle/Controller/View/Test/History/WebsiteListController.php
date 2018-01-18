<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\View\Test\ViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebsiteListController extends ViewController implements RequiresValidUser
{
    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $cacheableResponseService = $this->container->get('simplytestable.services.cacheableresponseservice');

        $user = $userService->getUser();

        $remoteTestService->setUser($user);
        $finishedWebsites = $remoteTestService->getFinishedWebsites();

        return $cacheableResponseService->getUncacheableResponse(new JsonResponse($finishedWebsites));
    }
}
