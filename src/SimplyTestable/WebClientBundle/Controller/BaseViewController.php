<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseViewController extends Controller implements IEFiltered
{
    /**
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

    /**
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        $userManager = $this->container->get(UserManager::class);

        return [
            'user' => $userManager->getUser(),
            'is_logged_in' => $userManager->isLoggedIn(),
        ];
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return !empty($this->response);
    }
}
