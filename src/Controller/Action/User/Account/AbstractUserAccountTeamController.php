<?php

namespace App\Controller\Action\User\Account;

use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractUserAccountTeamController extends AbstractUserAccountController
{
    /**
     * @return RedirectResponse
     */
    protected function createUserAccountTeamRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_user_account_team'));
    }
}
