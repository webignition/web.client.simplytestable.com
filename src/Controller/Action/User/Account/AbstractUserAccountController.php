<?php

namespace App\Controller\Action\User\Account;

use App\Controller\AbstractController;
use App\Interfaces\Controller\RequiresPrivateUser;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractUserAccountController extends AbstractController implements RequiresPrivateUser
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
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_sign_in',
            [
                'redirect' => base64_encode(json_encode(['route' => 'view_user_account']))
            ]
        ));
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
