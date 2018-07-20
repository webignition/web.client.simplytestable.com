<?php

namespace AppBundle\Controller\View\User\Account;

use AppBundle\Controller\View\User\AbstractUserController;
use AppBundle\Interfaces\Controller\RequiresPrivateUser;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\FlashBagValues;
use AppBundle\Services\TeamService;
use AppBundle\Services\UserManager;
use AppBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractUserAccountController extends AbstractUserController implements RequiresPrivateUser
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

    /**
     * @return string
     */
    abstract protected function getUserSignInRedirectResponseRoute();

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode(json_encode(['route' => $this->getUserSignInRedirectResponseRoute()]))
            ]
        ));
    }
}