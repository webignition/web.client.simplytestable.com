<?php

namespace App\Controller\Action\User\Account;

use App\Controller\AbstractController;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractUserAccountController extends AbstractController
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag
    ) {
        parent::__construct($router);

        $this->userManager = $userManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
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
