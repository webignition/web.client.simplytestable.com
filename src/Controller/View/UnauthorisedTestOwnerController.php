<?php

namespace App\Controller\View;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class UnauthorisedTestOwnerController extends AbstractBaseViewController
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UserManager $userManager,
        TestService $testService,
        RemoteTestService $remoteTestService,
        UrlViewValuesService $urlViewValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->userManager = $userManager;
        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->urlViewValues = $urlViewValues;
    }

    public function renderAction($test_id, $website)
    {
        $test = $this->testService->get($website, $test_id);

        if (!empty($test)) {
            return new RedirectResponse($this->router->generate(
                'view_test_results',
                [
                    'test_id' => $test_id,
                    'website' => $website
                ]
            ));
        }

        return $this->renderWithDefaultViewParameters(
            'test-results-not-authorised.html.twig',
            [
                'test_id' => $test_id,
                'website' => $this->urlViewValues->create($website),
            ]
        );
    }
}
