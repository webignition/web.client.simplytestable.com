<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

interface RequiresPrivateUser {

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUserSignInRedirectResponse();

}