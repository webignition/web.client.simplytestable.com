<?php

namespace App\Controller\View\Test\Results;

use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\DecoratedTest;
use App\Model\TestInterface;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskCollectionFilterService;
use App\Services\TaskService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    private $taskService;
    private $taskCollectionFilterService;
    private $userManager;
    private $testRetriever;

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

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        TaskTypeService $taskTypeService,
        TaskCollectionFilterService $taskCollectionFilterService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        TestRetriever $testRetriever
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheableResponseFactory,
            $remoteTestService,
            $taskTypeService,
            $testOptionsRequestAdapterFactory,
            $cssValidationTestConfiguration,
            $urlViewValues
        );

        $this->taskService = $taskService;
        $this->taskCollectionFilterService = $taskCollectionFilterService;
        $this->userManager = $userManager;
        $this->testRetriever = $testRetriever;
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
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $user = $this->userManager->getUser();
        $testModel = $this->testRetriever->retrieve($test_id);

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        if ($testModel->getRemoteTaskCount() > $testModel->getLocalTaskCount()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        if (TestInterface::STATE_EXPIRED === $testModel->getState()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_expired',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        $filter = trim($request->query->get('filter'));
        $taskType = trim($request->query->get('type'));
        $defaultFilter = $this->getDefaultRequestFilter(
            $testModel->getErrorCount(),
            $testModel->getWarningCount()
        );

        $filteredTaskCounts = $this->createFilteredTaskCounts($testModel->getEntity(), $taskType);

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

        $isPublicUserTest = $testModel->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $testModel->isPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'type' => $taskType,
            'filter' => $filter,
            'test_state' => $testModel->getState(),
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $remoteTaskIds = $this->getRemoteTaskIds(
            $testModel->getEntity(),
            $filter,
            $taskType
        );

        if (empty($remoteTaskIds)) {
            $remoteTaskIds = $testModel->getTaskIds();
        }

        $tasks = $this->taskService->getCollection($testModel->getEntity(), $testModel->getState(), $remoteTaskIds);

        $isOwner = in_array($user->getUsername(), $testModel->getOwners());

        $decoratedTest = new DecoratedTest($testModel);

        return $this->renderWithDefaultViewParameters(
            'test-results-available.html.twig',
            [
                'website' => $this->createWebsiteViewValues($website),
                'test' => $decoratedTest,
                'is_public' => $testModel->isPublic(),
                'is_public_user_test' => $isPublicUserTest,
                'is_owner' => $isOwner,
                'type' => $taskType,
                'type_label' => $this->getTaskTypeLabel($taskType),
                'filter' => $filter,
                'filter_label' => ucwords(str_replace('-', ' ', $filter)),
                'available_task_types' => $this->getAvailableTaskTypes(
                    $testModel->getTaskTypes(),
                    $testModel->isPublic(),
                    $isOwner
                ),
                'task_types' => $this->getTaskTypes(),
                'test_options' => $this->createTestOptions($testModel),
                'css_validation_ignore_common_cdns' => $this->getCssValidationExcludedDomains(),
                'tasks' => $tasks,
                'filtered_task_counts' => $filteredTaskCounts,
                'domain_test_count' => $this->getDomainTestCount($website),
                'default_css_validation_options' => [
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
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

    private function getDefaultRequestFilter(int $errorCount, int $warningCount): string
    {
        if ($errorCount > 0) {
            return 'with-errors';
        }

        if ($warningCount > 0) {
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
            ['css', 'html', 'link'],
            ['CSS', 'HTML', 'Link'],
            $taskType
        );

        return $taskTypeLabel;
    }

    private function createFilteredTaskCounts(Test $test, string $typeFilter): array
    {
        $taskCounts = [];

        $taskCounts['all'] = $this->taskCollectionFilterService->getRemoteIdCount($test, '', $typeFilter);

        $outcomeFilters = [
            self::FILTER_WITH_ERRORS,
            self::FILTER_WITH_WARNINGS,
            self::FILTER_WITHOUT_ERRORS,
            self::FILTER_SKIPPED,
            self::FILTER_CANCELLED,
        ];

        foreach ($outcomeFilters as $outcomeFilter) {
            $taskCountKey = str_replace('-', '_', $outcomeFilter);
            $taskCounts[$taskCountKey] = $this->taskCollectionFilterService->getRemoteIdCount(
                $test,
                $outcomeFilter,
                $typeFilter
            );
        }

        return $taskCounts;
    }

    /**
     * @param Test $test
     * @param string $filter
     * @param string $taskType
     *
     * @return int[]|null
     */
    private function getRemoteTaskIds(Test $test, $filter, $taskType)
    {
        if ($filter == 'all' && empty($taskType)) {
            return null;
        }

        return $this->taskCollectionFilterService->getRemoteIds($test, $filter, $taskType);
    }
}
