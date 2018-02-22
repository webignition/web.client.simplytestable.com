<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\Test\Task\ErrorTaskMapCollection;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ByTaskTypeController extends AbstractResultsController
{
    const FILTER_BY_PAGE = 'by-page';
    const FILTER_BY_ERROR = 'by-error';
    const DEFAULT_FILTER = self::FILTER_BY_ERROR;

    /**
     * @var string[]
     */
    private $allowedFilters = [
        self::FILTER_BY_PAGE,
        self::FILTER_BY_ERROR
    ];

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

        $router = $this->container->get('router');
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $urlViewValuesService = $this->container->get(UrlViewValuesService::class);
        $taskService = $this->container->get(TaskService::class);
        $taskCollectionFilterService = $this->container->get(TaskCollectionFilterService::class);
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $templating = $this->container->get('templating');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $requestTaskType = str_replace('+', ' ', $task_type);
        $selectedTaskType = $this->getSelectedTaskType($remoteTest, $requestTaskType);

        if (empty($selectedTaskType)) {
            return new RedirectResponse($router->generate(
                'view_test_results_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $hasValidFilter = in_array($filter, $this->allowedFilters);

        if (!$hasValidFilter) {
            return new RedirectResponse($router->generate(
                'view_test_results_bytasktype_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'task_type' => strtolower(str_replace(' ', '+', $selectedTaskType)),
                    'filter' => self::DEFAULT_FILTER
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_type' => $selectedTaskType,
            'filter' => $filter
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        if ($this->requiresPreparation($remoteTest, $test)) {
            return new RedirectResponse($router->generate(
                'view_test_results_preparing_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setOutcomeFilter('with-errors');
        $taskCollectionFilterService->setTypeFilter($selectedTaskType);

        $taskService->getCollection($test);
        $filteredRemoteTaskIds = $taskCollectionFilterService->getRemoteIds();

        $filteredTasks = $taskService->getCollection($test, $filteredRemoteTaskIds);
        $taskService->setParsedOutputOnCollection($filteredTasks);

        $tasks = $this->sortTasks($filteredTasks);

        $errorTaskMaps = new ErrorTaskMapCollection($tasks);
        $errorTaskMaps->sortMapsByOccurrenceCount()->sortByOccurrenceCount();

        $viewData = [
            'is_owner' => $remoteTestService->owns($user),
            'is_public_user_test' => $test->getUser() === SystemUserService::getPublicUser()->getUsername(),
            'website' => $urlViewValuesService->create($website),
            'test' => $test,
            'task_type' => $selectedTaskType,
            'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER,
            'tasks' => $tasks,
            'error_task_maps' => $errorTaskMaps
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/ByTaskType:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
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
        $remoteTestService = $this->container->get(RemoteTestService::class);

        $remoteTest = $remoteTestService->get();
        $filter = trim($request->attributes->get('filter'));
        $hasValidFilter = in_array($filter, $this->allowedFilters);

        return new RedirectResponse($router->generate(
            'view_test_results_bytasktype_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $request->attributes->get('test_id'),
                'task_type' => str_replace(' ', '+', $request->attributes->get('task_type')),
                'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
