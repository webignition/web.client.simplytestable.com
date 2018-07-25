<?php

namespace App\Controller\View\User\Account;

use App\Controller\View\User\AbstractUserController;
use App\Services\CacheValidatorService;
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UserService $userService
     * @param UserManager $userManager
     * @param TeamService $teamService
     * @param FlashBagValues $flashBagValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UserService $userService,
        UserManager $userManager,
        TeamService $teamService,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

        $this->userService = $userService;
        $this->userManager = $userManager;
        $this->teamService = $teamService;
    }
}
