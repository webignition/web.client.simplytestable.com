<?php

namespace App\Controller\View\Test\Results;

use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Services\CacheValidatorService;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\Configuration\JsStaticAnalysisTestConfiguration;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskCollectionFilterService;
use App\Services\TaskService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ResultsController extends AbstractResultsController
{
    const FILTER_WITH_ERRORS = 'with-errors';
    const FILTER_WITH_WARNINGS = 'with-warnings';
    const FILTER_WITHOUT_ERRORS = 'without-errors';
    const FILTER_ALL = 'all';
    const FILTER_SKIPPED = 'skipped';
    const FILTER_CANCELLED = 'cancelled';

    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @var TaskCollectionFilterService
     */
    private $taskCollectionFilterService;

    /**
     * @var TestOptionsRequestAdapterFactory
     */
    private $testOptionsRequestAdapterFactory;

    /**
     * @var CssValidationTestConfiguration
     */
    private $cssValidationTestConfiguration;

    /**
     * @var JsStaticAnalysisTestConfiguration
     */
    private $jsStaticAnalysisTestConfiguration;

    /**
     * @var string[]
     */
    private $filters = [
        self::FILTER_WITH_ERRORS,
        self::FILTER_WITH_WARNINGS,
        self::FILTER_WITHOUT_ERRORS,
        self::FILTER_ALL,
        self::FILTER_SKIPPED,
        self::FILTER_CANCELLED,
    ];

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskService $taskService
     * @param TaskTypeService $taskTypeService
     * @param TaskCollectionFilterService $taskCollectionFilterService
     * @param TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory
     * @param CssValidationTestConfiguration $cssValidationTestConfiguration
     * @param JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        SessionInterface $session,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        TaskTypeService $taskTypeService,
        TaskCollectionFilterService $taskCollectionFilterService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $urlViewValues,
            $userManager,
            $session
        );

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->taskTypeService = $taskTypeService;
        $this->taskCollectionFilterService = $taskCollectionFilterService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->jsStaticAnalysisTestConfiguration = $jsStaticAnalysisTestConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestWebsiteMismatchResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($this->generateUrl(
            'app_test_redirector',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
        ));
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $this->taskTypeService->setUser($user);
        if (!SystemUserService::isPublicUser($user)) {
            $this->taskTypeService->setUserIsAuthenticated();
        }

        if ($this->requiresPreparation($remoteTest, $test)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        $this->taskService->getCollection($test);

        $filter = trim($request->query->get('filter'));
        $taskType = trim($request->query->get('type'));
        $defaultFilter = $this->getDefaultRequestFilter($test);

        $this->taskCollectionFilterService->setTest($test);
        $this->taskCollectionFilterService->setTypeFilter($taskType);

        $filteredTaskCounts = $this->createFilteredTaskCounts();

        if (!$this->isFilterValid($filter, $filteredTaskCounts)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'filter' => $defaultFilter
                ]
            ));
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'type' => $taskType,
            'filter' => $filter,
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $remoteTaskIds = $this->getRemoteTaskIds(
            $filter,
            $taskType
        );

        $tasks = $this->taskService->getCollection($test, $remoteTaskIds);

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData($remoteTest->getOptions());
        $testOptionsAdapter->setInvertInvertableOptions(true);

        $isOwner = $this->remoteTestService->owns($user);

        return $this->renderWithDefaultViewParameters(
            'test-results.html.twig',
            [
                'website' => $this->urlViewValues->create($website),
                'test' => $test,
                'is_public' => $remoteTest->getIsPublic(),
                'is_public_user_test' => $isPublicUserTest,
                'remote_test' => $remoteTest,
                'is_owner' => $isOwner,
                'type' => $taskType,
                'type_label' => $this->getTaskTypeLabel($taskType),
                'filter' => $filter,
                'filter_label' => ucwords(str_replace('-', ' ', $filter)),
                'available_task_types' => $this->getAvailableTaskTypes($remoteTest, $isOwner),
                'task_types' => $this->taskTypeService->get(),
                'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
                'css_validation_ignore_common_cdns' =>
                    $this->cssValidationTestConfiguration->getExcludedDomains(),
                'js_static_analysis_ignore_common_cdns' =>
                    $this->jsStaticAnalysisTestConfiguration->getExcludedDomains(),
                'tasks' => $tasks,
                'filtered_task_counts' => $filteredTaskCounts,
                'domain_test_count' => $this->remoteTestService->getFinishedCount($website),
                'default_css_validation_options' => [
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
                ],
                'default_js_static_analysis_options' => [
                    'ignore-common-cdns' => 1,
                    'jslint-option-maxerr' => 50,
                    'jslint-option-indent' => 4,
                    'jslint-option-maxlen' => 256
                ],
            ],
            $response
        );
    }

    /**
     * @param string $filter
     * @param array $filteredTaskCounts
     *
     * @return bool
     */
    private function isFilterValid($filter, array $filteredTaskCounts)
    {
        if (!in_array($filter, $this->filters)) {
            return false;
        }

        $modifiedFilter = str_replace('-', '_', $filter);

        return $filteredTaskCounts[$modifiedFilter] > 0;
    }


    /**
     * @param Test $test
     *
     * @return string
     */
    private function getDefaultRequestFilter(Test $test)
    {
        if ($test->hasErrors()) {
            return 'with-errors';
        }

        if ($test->hasWarnings()) {
            return 'with-warnings';
        }

        return 'without-errors';
    }

    /**
     * @param $taskType
     *
     * @return string
     */
    private function getTaskTypeLabel($taskType)
    {
        if (empty($taskType)) {
            return 'All';
        }

        $taskTypeLabel = str_replace(
            ['css', 'html', 'js', 'link'],
            ['CSS', 'HTML', 'JS', 'Link'],
            $taskType
        );

        return $taskTypeLabel;
    }

    /**
     * @return array
     */
    private function createFilteredTaskCounts()
    {
        $filteredTaskCounts = [];

        $this->taskCollectionFilterService->setOutcomeFilter(null);
        $filteredTaskCounts['all'] = $this->taskCollectionFilterService->getRemoteIdCount();

        $filters = [
            self::FILTER_WITH_ERRORS,
            self::FILTER_WITH_WARNINGS,
            self::FILTER_WITHOUT_ERRORS,
            self::FILTER_SKIPPED,
            self::FILTER_CANCELLED,
        ];

        foreach ($filters as $filter) {
            $this->taskCollectionFilterService->setOutcomeFilter($filter);

            $filteredTaskCountKey = str_replace('-', '_', $filter);
            $filteredTaskCounts[$filteredTaskCountKey] = $this->taskCollectionFilterService->getRemoteIdCount();
        }

        return $filteredTaskCounts;
    }

    /**
     * @param string $filter
     * @param string $taskType
     *
     * @return int[]|null
     */
    private function getRemoteTaskIds($filter, $taskType)
    {
        if ($filter == 'all' && empty($taskType)) {
            return null;
        }

        $this->taskCollectionFilterService->setOutcomeFilter($filter);
        $this->taskCollectionFilterService->setTypeFilter($taskType);

        return $this->taskCollectionFilterService->getRemoteIds();
    }

    /**
     * @param RemoteTest $remoteTest
     * @param bool $isOwner
     *
     * @return array
     */
    private function getAvailableTaskTypes(RemoteTest $remoteTest, $isOwner)
    {
        if ($remoteTest->getIsPublic() && !$isOwner) {
            $availableTaskTypes = $this->taskTypeService->get();
            $remoteTestTaskTypes = $remoteTest->getTaskTypes();

            foreach ($availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                if (!in_array($taskTypeDetails['name'], $remoteTestTaskTypes)) {
                    unset($availableTaskTypes[$taskTypeKey]);
                }
            }

            return $availableTaskTypes;
        }

        return $this->taskTypeService->getAvailable();
    }
}
