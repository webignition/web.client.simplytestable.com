<?php

namespace AppBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

interface RequiresPrivateUser extends RequiresValidUser
{
    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request);
}