<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\Task\Collection as TaskCollection;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\TaskService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestTaskListController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param TaskService $taskService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        TaskService $taskService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->testService = $testService;
        $this->taskService = $taskService;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
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

        $requestData = $request->request;

        $test = $this->testService->get($website, $test_id);
        $taskIds = $this->getTaskIdsFromRequest($requestData);

        if (empty($taskIds)) {
            return new Response('');
        }

        $tasks = $this->taskService->getCollection($test, $taskIds);
        $taskCollection = new TaskCollection($tasks);

        if ($taskCollection->isEmpty()) {
            return new Response('');
        }

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_collection_hash' => $taskCollection->getHash(),
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'test' => $test,
            'tasks' => $tasks,
            'page_index' => $requestData->get('pageIndex'),
        ];

        return $this->renderWithDefaultViewParameters(
            'Partials/Test/TaskList/task-list.html.twig',
            $viewData,
            $response
        );
    }

    /**
     * @param ParameterBag $requestData
     *
     * @return int[]
     */
    private function getTaskIdsFromRequest(ParameterBag $requestData)
    {
        $taskIds = [];
        $requestTaskIds = $requestData->get('taskIds');

        if (!is_array($requestTaskIds)) {
            return [];
        }

        foreach ($requestTaskIds as $taskId) {
            if (is_int($taskId) || ctype_digit($taskId)) {
                $taskIds[] = (int)$taskId;
            }
        }

        return $taskIds;
    }
}
