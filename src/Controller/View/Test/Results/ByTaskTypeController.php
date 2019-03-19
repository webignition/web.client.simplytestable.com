<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Task\Task;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Model\Test\Task\ErrorTaskMapCollection;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskCollectionFilterService;
use App\Services\TaskService;
use App\Services\TestFactory;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ByTaskTypeController extends AbstractBaseViewController
{
    const FILTER_BY_PAGE = 'by-page';
    const FILTER_BY_ERROR = 'by-error';
    const DEFAULT_FILTER = self::FILTER_BY_ERROR;

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
     * @var TaskCollectionFilterService
     */
    private $taskCollectionFilterService;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    /**
     * @var UserManager
     */
    private $userManager;

    private $testFactory;

    /**
     * @var string[]
     */
    private $allowedFilters = [
        self::FILTER_BY_PAGE,
        self::FILTER_BY_ERROR
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
        TaskCollectionFilterService $taskCollectionFilterService,
        TestFactory $testFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->taskCollectionFilterService = $taskCollectionFilterService;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->testFactory = $testFactory;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     * @param string $task_type
     * @param string|null $filter
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(
        Request $request,
        string $website,
        int $test_id,
        string $task_type,
        ?string $filter = null
    ): Response {
        $user = $this->userManager->getUser();
        $test = $this->testService->get($test_id);

        if (empty($test)) {
            return new RedirectResponse($this->generateUrl('view_dashboard'));
        }

        $remoteTest = $this->remoteTestService->get($test->getTestId());

        $requestTaskType = str_replace('+', ' ', $task_type);
        $selectedTaskType = $this->getSelectedTaskType($remoteTest, $requestTaskType);

        if (empty($selectedTaskType)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $hasValidFilter = in_array($filter, $this->allowedFilters);

        if (!$hasValidFilter) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_by_task_type_filter',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'task_type' => strtolower(str_replace(' ', '+', $selectedTaskType)),
                    'filter' => self::DEFAULT_FILTER
                ]
            ));
        }

        if ($website !== $test->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_by_task_type_filter',
                [
                    'website' => $remoteTest->getWebsite(),
                    'test_id' => $test_id,
                    'task_type' => str_replace(' ', '+', $request->attributes->get('task_type')),
                    'filter' => $filter
                ]
            ));
        }

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_type' => $selectedTaskType,
            'filter' => $filter
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        if ($remoteTest->getTaskCount() > $test->getTaskCount()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        $this->taskCollectionFilterService->setTest($test);
        $this->taskCollectionFilterService->setOutcomeFilter('with-errors');
        $this->taskCollectionFilterService->setTypeFilter($selectedTaskType);

        $filteredRemoteTaskIds = $this->taskCollectionFilterService->getRemoteIds();

        $filteredTasks = $this->taskService->getCollection($test, $filteredRemoteTaskIds);
        $this->taskService->setParsedOutputOnCollection($filteredTasks);

        $tasks = $this->sortTasks($filteredTasks);

        $errorTaskMaps = new ErrorTaskMapCollection($tasks);
        $errorTaskMaps->sortMapsByOccurrenceCount();
        $errorTaskMaps->sortByOccurrenceCount();

        $testModel = $this->testFactory->create($test, $remoteTest, [
            'website' => $remoteTest->getWebsite(),
            'user' => $remoteTest->getUser(),
            'state' => $remoteTest->getState(),
            'type' => $remoteTest->getType(),
            'url_count' => $remoteTest->getUrlCount(),
            'task_count' => $remoteTest->getTaskCount(),
            'errored_task_count' => $remoteTest->getErroredTaskCount(),
            'cancelled_task_count' => $remoteTest->getCancelledTaskCount(),
            'parameters' => $remoteTest->getEncodedParameters(),
            'amendments' => $remoteTest->getAmmendments(),
            'crawl' => $remoteTest->getCrawl(),
            'task_types' => $remoteTest->getTaskTypes(),
            'task_count_by_state' => $remoteTest->getRawTaskCountByState(),
            'rejection' => [],
        ]);
        $decoratedTest = new DecoratedTest($testModel);

        return $this->renderWithDefaultViewParameters(
            'test-results-by-task-type.html.twig',
            [
                'is_owner' => $remoteTest->getOwners()->contains($user->getUsername()),
                'is_public_user_test' => $test->getUser() === SystemUserService::getPublicUser()->getUsername(),
                'website' => $this->urlViewValues->create($website),
                'test' => $decoratedTest,
                'task_type' => $selectedTaskType,
                'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER,
                'tasks' => $tasks,
                'error_task_maps' => $errorTaskMaps
            ],
            $response
        );
    }

    /**
     * @param Task[] $tasks
     *
     * @return Task[]
     */
    private function sortTasks($tasks)
    {
        $index = [];

        foreach ($tasks as $taskIndex => $task) {
            $index[$taskIndex] = $task->getOutput()->getResult()->getErrorCount();
        }

        arsort($index);

        $sortedTasks = [];

        foreach ($index as $taskIndex => $value) {
            $sortedTasks[$taskIndex] = $tasks[$taskIndex];
        }

        return $sortedTasks;
    }

    /**
     * @param RemoteTest $remoteTest
     * @param string $requestTaskType
     *
     * @return string|null
     */
    private function getSelectedTaskType(RemoteTest $remoteTest, $requestTaskType)
    {
        $remoteTaskTypes = $remoteTest->getTaskTypes();

        foreach ($remoteTaskTypes as $remoteTaskType) {
            if (strtolower($remoteTaskType) == strtolower($requestTaskType)) {
                return $remoteTaskType;
            }
        }

        return null;
    }
}
