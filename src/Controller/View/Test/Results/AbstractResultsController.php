<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractResultsController extends AbstractBaseViewController
{
    private $remoteTestService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->remoteTestService = $remoteTestService;
    }

    protected function getDomainTestCount(string $website)
    {
        return $this->remoteTestService->getFinishedCount($website);
    }
}
