<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

interface RequiresPrivateUser
{
    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request);

    /**
     * @param Response $response
     */
    public function setResponse(Response $response);

    /**
     * @return bool
     */
    public function hasResponse();
}
