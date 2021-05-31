<?php

namespace App\Controller\View;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class UnauthorisedTestOwnerController extends AbstractBaseViewController
{
    private $urlViewValues;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->urlViewValues = $urlViewValues;
        $this->testRetriever = $testRetriever;
    }

    public function renderAction(int $test_id, string $website)
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        if (!empty($testModel)) {
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
