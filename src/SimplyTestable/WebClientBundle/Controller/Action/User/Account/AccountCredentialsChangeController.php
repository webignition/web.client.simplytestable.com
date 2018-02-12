<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AccountCredentialsChangeController extends Controller implements RequiresPrivateUser
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], true));
    }
}
