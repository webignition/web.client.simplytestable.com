<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\Test\Task\ErrorTaskMapCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ByTaskTypeController extends ResultsController
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
     * @return Response
     *
     * @throws WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id, $task_type, $filter = null)
    {
        $router = $this->container->get('router');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');
        $taskService = $this->container->get('simplytestable.services.taskservice');
        $taskCollectionFilterService = $this->container->get('simplytestable.services.taskcollectionfilterservice');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $requestTaskType = str_replace('+', ' ', $task_type);
        $selectedTaskType = $this->getSelectedTaskType($remoteTest, $requestTaskType);

        if (empty($selectedTaskType)) {
            $redirectUrl = $router->generate(
                'view_test_results_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $hasValidFilter = in_array($filter, $this->allowedFilters);

        if (!$hasValidFilter) {
            $redirectUrl = $router->generate(
                'view_test_results_bytasktype_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'task_type' => strtolower(str_replace(' ', '+', $selectedTaskType)),
                    'filter' => self::DEFAULT_FILTER
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
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
            $redirectUrl = $router->generate(
                'view_test_results_preparing_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
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
            'is_owner' => $remoteTestService->owns(),
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
            'website' => $urlViewValuesService->create($website),
            'test' => $test,
            'task_type' => $selectedTaskType,
            'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER,
            'tasks' => $tasks,
            'error_task_maps' => $errorTaskMaps
        ];

        $templating = $this->container->get('templating');

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
    public function getRequestWebsiteMismatchResponse(Request $request)
    {
        $router = $this->container->get('router');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');

        $remoteTest = $remoteTestService->get();
        $filter = trim($request->attributes->get('filter'));
        $hasValidFilter = in_array($filter, $this->allowedFilters);

        $redirectUrl = $router->generate(
            'view_test_results_bytasktype_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $request->attributes->get('test_id'),
                'task_type' => str_replace(' ', '+', $request->attributes->get('task_type')),
                'filter' => $hasValidFilter ? $filter : self::DEFAULT_FILTER
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');
        $session = $this->container->get('session');
        $router = $this->container->get('router');

        $website = $request->attributes->get('website');
        $testId = $request->attributes->get('test_id');

        if ($userService->isLoggedIn()) {
            return $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Results:not-authorised.html.twig',
                array_merge($this->getDefaultViewParameters(), [
                    'test_id' => $testId,
                    'website' => $urlViewValuesService->create($website),
                ])
            );
        }

        $redirectParameters = json_encode([
            'route' => 'view_test_progress_index_index',
            'parameters' => [
                'website' => $website,
                'test_id' => $testId,
            ]
        ]);

        $session->getFlashBag()->set('user_signin_error', 'test-not-logged-in');

        $redirectUrl = $router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode($redirectParameters)
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }
}
