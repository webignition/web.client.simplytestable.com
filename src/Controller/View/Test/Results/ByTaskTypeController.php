<?php

namespace App\Controller\View\Test\Results;

use App\Entity\Task\Task;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\Task\ErrorTaskMapCollection;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskCollectionFilterService;
use App\Services\TaskService;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ByTaskTypeController extends AbstractResultsController
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
     * @var string[]
     */
    private $allowedFilters = [
        self::FILTER_BY_PAGE,
        self::FILTER_BY_ERROR
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
     * @param TaskCollectionFilterService $taskCollectionFilterService
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
        TaskCollectionFilterService $taskCollectionFilterService
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
        $this->taskCollectionFilterService = $taskCollectionFilterService;
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
    public function indexAction(Request $request, $website, $test_id, $task_type, $filter = null)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $requestTaskType = str_replace('+', ' ', $task_type);
        $selectedTaskType = $this->getSelectedTaskType($remoteTest, $requestTaskType);

        if (empty($selectedTaskType)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $hasValidFilter = in_array($filter, $this->allowedFilters);

        if (!$hasValidFilter) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_bytasktype_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'task_type' => strtolower(str_replace(' ', '+', $selectedTaskType)),
                    'filter' => self::DEFAULT_FILTER
                ]
            ));
        }

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_type' => $selectedTaskType,
            'filter' => $filter
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
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

        $this->taskCollectionFilterService->setTest($test);
        $this->taskCollectionFilterService->setOutcomeFilter('with-errors');
        $this->taskCollectionFilterService->setTypeFilter($selectedTaskType);

        $this->taskService->getCollection($test);
        $filteredRemoteTaskIds = $this->taskCollectionFilterService->getRemoteIds();

        $filteredTasks = $this->taskService->getCollection($test, $filteredRemoteTaskIds);
        $this->taskService->setParsedOutputOnCollection($filteredTasks);

        $tasks = $this->sortTasks($filteredTasks);

        $errorTaskMaps = new ErrorTaskMapCollection($tasks);
        $errorTaskMaps->sortMapsByOccurrenceCount();
        $errorTaskMaps->sortByOccurrenceCount();

        return $this->renderWithDefaultViewParameters(
            'test-results-by-task-type.html.twig',
            [
                'is_owner' => $this->remoteTestService->owns($user),
                'is_public_user_test' => $test->getUser() === SystemUserService::getPublicUser()->getUsername(),
                'website' => $this->urlViewValues->create($website),
                'test' => $test,
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

    /**
     * {@inheritdoc}
     */
    public function getRequestWebsiteMismatchResponse(RouterInterface $router, Request $request)
    {
        $remoteTest = $this->remoteTestService->get();
        $filter = trim($request->attributes->get('filter'));
        $hasValidFilter = in_array($filter, $this->allowedFilters);

        return new RedirectResponse($this->generateUrl(
            'view_test_results_bytasktype_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $request->attributes->get('test_id'),
                'task_type' => str_replace(' ', '+', $request->attributes->get('task_type')),
                'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER
            ]
        ));
    }
}
