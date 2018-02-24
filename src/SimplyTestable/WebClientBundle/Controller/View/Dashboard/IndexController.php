<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\CssValidationTestConfiguration;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\JsStaticAnalysisTestConfiguration;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapter as TestOptionsRequestAdapter;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use SimplyTestable\WebClientBundle\Services\TestOptionsConfiguration;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class IndexController extends BaseViewController implements RequiresValidUser
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
     * @var CacheValidatorService
     */
    private $cacheValidator;

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

    /**
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param TaskTypeService $taskTypeService
     * @param TestOptionsRequestAdapterFactory $testOptionsAdapterFactory
     * @param UrlViewValuesService $urlViewValuesService
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     * @param UserManager $userManager
     * @param TestOptionsConfiguration $testOptionsConfiguration
     * @param CssValidationTestConfiguration $cssValidationTestConfiguration
     * @param JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
     */
    public function __construct(
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsAdapterFactory,
        UrlViewValuesService $urlViewValuesService,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues,
        UserManager $userManager,
        TestOptionsConfiguration $testOptionsConfiguration,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
    ) {
        parent::__construct($twig, $defaultViewParameters);

        $this->taskTypeService = $taskTypeService;
        $this->testOptionsAdapterFactory = $testOptionsAdapterFactory;
        $this->urlViewValuesService = $urlViewValuesService;
        $this->cacheValidator = $cacheValidator;
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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();
        $isLoggedIn = !SystemUserService::isPublicUser($user);

        $this->taskTypeService->setUser($user);
        if ($isLoggedIn) {
            $this->taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $request->query;
        $testOptionsAdapter = $this->testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

        $testStartError = $this->flashBagValues->getSingle('test_start_error');
        $hasTestStartError = !empty($testStartError);

        $website = $requestData->get('website');
        $availableTaskTypes = $this->taskTypeService->getAvailable();
        $taskTypes = $this->taskTypeService->get();
        $testOptions = $this->getTestOptionsArray($testOptionsAdapter, $requestData, $hasTestStartError);

        $cssValidationExcludedDomains = $this->cssValidationTestConfiguration->getExcludedDomains();
        $jsStaticAnalysisExcludedDomains = $this->jsStaticAnalysisTestConfiguration->getExcludedDomains();

        $response = $this->cacheValidator->createResponse($request, [
            'test_start_error' => $testStartError,
            'website' => $website,
            'available_task_types' => json_encode($availableTaskTypes),
            'task_types' => json_encode($taskTypes),
            'test_options' => json_encode($testOptions),
            'css_validation_ignore_common_cdns' => json_encode($cssValidationExcludedDomains),
            'js_static_analysis_ignore_common_cdns' => json_encode($jsStaticAnalysisExcludedDomains),
            'is_logged_in' => $isLoggedIn,
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'available_task_types' => $availableTaskTypes,
            'task_types' => $taskTypes,
            'test_options' => $testOptions,
            'css_validation_ignore_common_cdns' => $cssValidationExcludedDomains,
            'js_static_analysis_ignore_common_cdns' => $jsStaticAnalysisExcludedDomains,
            'test_start_error' => $testStartError,
            'website' => $this->urlViewValuesService->create($website),
        ];

        $content = $this->twig->render(
            'SimplyTestableWebClientBundle:bs3/Dashboard/Index:index.html.twig',
            array_merge($this->defaultViewParameters->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }

    /**
     * @param TestOptionsRequestAdapter $testOptionsAdapter
     * @param ParameterBag $requestData
     * @param bool $hasTestStartError
     *
     * @return array
     */
    private function getTestOptionsArray(
        TestOptionsRequestAdapter $testOptionsAdapter,
        ParameterBag $requestData,
        $hasTestStartError
    ) {
        $testOptionsConfiguration = $this->testOptionsConfiguration->getConfiguration();

        $testOptionsData = array_merge(
            $testOptionsConfiguration['names_and_default_values'],
            $requestData->all()
        );

        $testOptionsAdapter->setRequestData(new ParameterBag($testOptionsData));

        if ($hasTestStartError) {
            $testOptionsAdapter->setInvertInvertableOptions(true);
        }

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
