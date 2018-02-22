<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;

abstract class AbstractUserAccountController extends BaseViewController implements RequiresPrivateUser
{
}
