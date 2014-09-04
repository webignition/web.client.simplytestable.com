<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

abstract class AccountCredentialsChangeController extends BaseController implements RequiresPrivateUser {

    const ONE_YEAR_IN_SECONDS = 31536000;

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUserSignInRedirectResponse() {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], true));
    }


    /**
     * @return Cookie
     */
    protected function getUserAuthenticationCookie() {
        return new Cookie(
            'simplytestable-user',
            $this->getUserSerializerService()->serializeToString($this->getUser()),
            time() + self::ONE_YEAR_IN_SECONDS,
            '/',
            '.simplytestable.com',
            false,
            true
        );
    }




}