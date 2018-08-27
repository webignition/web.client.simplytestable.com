<?php

namespace App\Controller\View\User;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractUserController extends AbstractBaseViewController
{
    /**
     * @var FlashBagValues
     */
    protected $flashBagValues;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->flashBagValues = $flashBagValues;
    }
}
