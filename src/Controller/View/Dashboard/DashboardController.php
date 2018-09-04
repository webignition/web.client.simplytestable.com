<?php

namespace App\Controller\View\Dashboard;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\Configuration\JsStaticAnalysisTestConfiguration;
use App\Services\SystemUserService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapter as TestOptionsRequestAdapter;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\Configuration\TestOptionsConfiguration;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class DashboardController extends AbstractBaseViewController
{
    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @var TestOptionsRequestAdapterFactory
     */
    private $testOptionsAdapterFactory;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValuesService;

    /**
     * @var FlashBagValues
     */
    private $flashBagValues;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var TestOptionsConfiguration
     */
    private $testOptionsConfiguration;

    /**
     * @var CssValidationTestConfiguration
     */
    private $cssValidationTestConfiguration;

    /**
     * @var JsStaticAnalysisTestConfiguration
     */
    private $jsStaticAnalysisTestConfiguration;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsAdapterFactory,
        UrlViewValuesService $urlViewValuesService,
        CacheableResponseFactory $cacheableResponseFactory,
        FlashBagValues $flashBagValues,
        UserManager $userManager,
        TestOptionsConfiguration $testOptionsConfiguration,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->taskTypeService = $taskTypeService;
        $this->testOptionsAdapterFactory = $testOptionsAdapterFactory;
        $this->urlViewValuesService = $urlViewValuesService;
        $this->flashBagValues = $flashBagValues;
        $this->userManager = $userManager;
        $this->testOptionsConfiguration = $testOptionsConfiguration;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->jsStaticAnalysisTestConfiguration = $jsStaticAnalysisTestConfiguration;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $user = $this->userManager->getUser();

        $requestData = $request->query;
        $testOptionsAdapter = $this->testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

        $testStartError = $this->flashBagValues->getSingle('test_start_error');

        $website = $requestData->get('website');
        $availableTaskTypes = $this->taskTypeService->getAvailable();

        $taskTypes = $this->taskTypeService->get();
        $testOptions = $this->getTestOptionsArray($testOptionsAdapter, $requestData);

        $cssValidationExcludedDomains = $this->cssValidationTestConfiguration->getExcludedDomains();
        $jsStaticAnalysisExcludedDomains = $this->jsStaticAnalysisTestConfiguration->getExcludedDomains();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'test_start_error' => $testStartError,
            'website' => $website,
            'available_task_types' => json_encode($availableTaskTypes),
            'task_types' => json_encode($taskTypes),
            'test_options' => json_encode($testOptions),
            'css_validation_ignore_common_cdns' => json_encode($cssValidationExcludedDomains),
            'js_static_analysis_ignore_common_cdns' => json_encode($jsStaticAnalysisExcludedDomains),
            'is_logged_in' => !SystemUserService::isPublicUser($user),
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters(
            'dashboard.html.twig',
            [
                'available_task_types' => $availableTaskTypes,
                'task_types' => $taskTypes,
                'test_options' => $testOptions,
                'css_validation_ignore_common_cdns' => $cssValidationExcludedDomains,
                'js_static_analysis_ignore_common_cdns' => $jsStaticAnalysisExcludedDomains,
                'test_start_error' => $testStartError,
                'website' => $this->urlViewValuesService->create($website),
            ],
            $response
        );
    }

    /**
     * @param TestOptionsRequestAdapter $testOptionsAdapter
     * @param ParameterBag $requestData
     *
     * @return array
     */
    private function getTestOptionsArray(TestOptionsRequestAdapter $testOptionsAdapter, ParameterBag $requestData)
    {
        $testOptionsConfiguration = $this->testOptionsConfiguration->getConfiguration();

        $testOptionsData = array_merge(
            $testOptionsConfiguration['names_and_default_values'],
            $requestData->all()
        );

        $testOptionsAdapter->setRequestData(new ParameterBag($testOptionsData));

        $testOptions = $testOptionsAdapter->getTestOptions()->__toKeyArray();

        if (!isset($testOptions['cookies']) || count($testOptions['cookies']) === 0) {
            $testOptions['cookies'] = [[
                'name' => null,
                'value' => null
            ]];
        }

        return $testOptions;
    }
}
