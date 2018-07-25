<?php

namespace App\Controller\View\Test;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractRequiresValidOwnerController extends AbstractBaseViewController
{
    /**
     * @var UrlViewValuesService
     */
    protected $urlViewValues;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        SessionInterface $session
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->session = $session;
    }
}
