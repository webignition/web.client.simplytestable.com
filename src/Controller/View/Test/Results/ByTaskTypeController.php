<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Task\Task;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\DecoratedTest;
use App\Model\Test\Task\ErrorTaskMapCollection;
use App\Model\TestInterface;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\SystemUserService;
use App\Services\TaskCollectionFilterService;
use App\Services\TaskService;
use App\Services\TestFactory;
use App\Services\TestRetriever;
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

    private $taskService;
    private $taskCollectionFilterService;
    private $urlViewValues;
    private $userManager;
    private $testFactory;
    private $testRetriever;

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
        TaskService $taskService,
        TaskCollectionFilterService $taskCollectionFilterService,
        TestFactory $testFactory,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->taskService = $taskService;
        $this->taskCollectionFilterService = $taskCollectionFilterService;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->testFactory = $testFactory;
        $this->testRetriever = $testRetriever;
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
        $testModel = $this->testRetriever->retrieve($test_id);

        if (TestInterface::STATE_EXPIRED === $testModel->getState()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $requestTaskType = str_replace('+', ' ', $task_type);
        $selectedTaskType = $this->getSelectedTaskType($testModel->getTaskTypes(), $requestTaskType);

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

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_by_task_type_filter',
                [
                    'website' => $testModel->getWebsite(),
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
            'filter' => $filter,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
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

        $filteredRemoteTaskIds = $this->taskCollectionFilterService->getRemoteIds(
            $testModel->getEntity(),
            'with-errors',
            $selectedTaskType
        );

        $filteredTasks = $this->taskService->getCollection(
            $testModel->getEntity(),
            $testModel->getState(),
            $filteredRemoteTaskIds
        );

        $this->taskService->setParsedOutputOnCollection($filteredTasks);

        $tasks = $this->sortTasks($filteredTasks);

        $errorTaskMaps = new ErrorTaskMapCollection($tasks);
        $errorTaskMaps->sortMapsByOccurrenceCount();
        $errorTaskMaps->sortByOccurrenceCount();

        $decoratedTest = new DecoratedTest($testModel);

        $isOwner = in_array($user->getUsername(), $testModel->getOwners());

        return $this->renderWithDefaultViewParameters(
            'test-results-by-task-type.html.twig',
            [
                'is_owner' => $isOwner,
                'is_public_user_test' => $testModel->getUser() === SystemUserService::getPublicUser()->getUsername(),
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

    private function getSelectedTaskType(array $taskTypes, string $requestTaskType): ?string
    {
        foreach ($taskTypes as $taskType) {
            if (strtolower($taskType) == strtolower($requestTaskType)) {
                return $taskType;
            }
        }

        return null;
    }
}
