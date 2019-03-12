<?php

namespace App\Controller\View\Test\Results;

use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
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
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    /**
     * @var UserManager
     */
    private $userManager;

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
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        TaskTypeService $taskTypeService,
        TaskCollectionFilterService $taskCollectionFilterService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->taskTypeService = $taskTypeService;
        $this->taskCollectionFilterService = $taskCollectionFilterService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
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
        $user = $this->userManager->getUser();
        $test = $this->testService->get($website, $test_id);

        if (empty($test)) {
            return new RedirectResponse($this->generateUrl('view_dashboard'));
        }

        $remoteTest = $this->remoteTestService->get($test->getTestId());

        if (empty($remoteTest)) {
            return new RedirectResponse($this->generateUrl('view_dashboard'));
        }

        if ($website !== $remoteTest->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'redirect_website_test',
                [
                    'website' => $remoteTest->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        if ($this->requiresPreparation($remoteTest, $test)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing',
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

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'type' => $taskType,
            'filter' => $filter,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $remoteTaskIds = $this->getRemoteTaskIds(
            $filter,
            $taskType
        );

        $tasks = $this->taskService->getCollection($test, $remoteTaskIds);

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData($remoteTest->getOptions());

        $isOwner = $remoteTest->getOwners()->contains($user->getUsername());

        $decoratedTest = new DecoratedTest($test, $remoteTest);

        return $this->renderWithDefaultViewParameters(
            'test-results.html.twig',
            [
                'website' => $this->urlViewValues->create($website),
                'test' => $decoratedTest,
                'is_public' => $remoteTest->getIsPublic(),
                'is_public_user_test' => $isPublicUserTest,
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
                'tasks' => $tasks,
                'filtered_task_counts' => $filteredTaskCounts,
                'domain_test_count' => $this->remoteTestService->getFinishedCount($website),
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
            ['css', 'html', 'link'],
            ['CSS', 'HTML', 'Link'],
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
