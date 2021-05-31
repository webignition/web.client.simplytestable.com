<?php

namespace App\Controller\View\User\Account;

use App\Controller\View\User\AbstractUserController;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\TeamService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractUserAccountController extends AbstractUserController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var TeamService
     */
    protected $teamService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UserService $userService,
        UserManager $userManager,
        TeamService $teamService,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory, $flashBagValues);

        $this->userService = $userService;
        $this->userManager = $userManager;
        $this->teamService = $teamService;
    }
}
