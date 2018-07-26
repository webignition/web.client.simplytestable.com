<?php

namespace App\Controller\Action\User\Account;

use App\Controller\AbstractController;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractUserAccountController extends AbstractController
{
    /**
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session
    ) {
        parent::__construct($router);

        $this->userManager = $userManager;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return !empty($this->response);
    }

    /**
     * @param string|null $urlSuffix
     *
     * @return RedirectResponse
     */
    protected function createUserAccountRedirectResponse($urlSuffix = null)
    {
        $url = $this->generateUrl('view_user_account');

        if ($urlSuffix) {
            $url .= $urlSuffix;
        }

        return new RedirectResponse($url);
    }
}
