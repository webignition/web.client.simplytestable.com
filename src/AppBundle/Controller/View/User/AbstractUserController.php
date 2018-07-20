<?php

namespace AppBundle\Controller\View\User;

use AppBundle\Controller\BaseViewController;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\FlashBagValues;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractUserController extends BaseViewController
{
    /**
     * @var FlashBagValues
     */
    protected $flashBagValues;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->flashBagValues = $flashBagValues;
    }
}
