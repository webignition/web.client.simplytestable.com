<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface RequiresPrivateUser
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse(Request $request);
}
