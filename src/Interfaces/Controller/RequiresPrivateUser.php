<?php

namespace App\Interfaces\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

interface RequiresPrivateUser extends SettableResponse
{
    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request);
}
