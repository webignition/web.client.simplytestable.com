<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\Task\Collection as TaskCollection;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\TaskService;
use App\Services\TestRetriever;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestTaskListController extends AbstractBaseViewController
{
    private $taskService;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TaskService $taskService,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->taskService = $taskService;
        $this->testRetriever = $testRetriever;
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
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $requestData = $request->request;
        $taskIds = $this->getTaskIdsFromRequest($requestData);

        if (empty($taskIds)) {
            return new Response('');
        }

        $test = $this->testRetriever->retrieve($test_id);
        $tasks = $this->taskService->getCollection($test->getEntity(), $test->getState(), $taskIds);
        $taskCollection = new TaskCollection($tasks);

        if ($taskCollection->isEmpty()) {
            return new Response('');
        }

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_collection_hash' => $taskCollection->getHash(),
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
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
