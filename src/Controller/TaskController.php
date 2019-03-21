<?php

namespace App\Controller;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\TaskService;
use App\Services\TestRetriever;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    const DEFAULT_UNRETRIEVED_TASK_ID_LIMIT = 100;
    const MAX_UNRETRIEVED_TASK_ID_LIMIT = 1000;

    private $taskService;
    private $testRetriever;

    public function __construct(TaskService $taskService, TestRetriever $testRetriever)
    {
        $this->taskService = $taskService;
        $this->testRetriever = $testRetriever;
    }

    /**
     * @param int $test_id
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function idCollectionAction(int $test_id): JsonResponse
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        return new JsonResponse($testModel->getTaskIds());
    }

    /**
     * @param int $test_id
     * @param int|null $limit
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function unretrievedIdCollectionAction(int $test_id, ?int $limit = null): JsonResponse
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        $limit = filter_var($limit, FILTER_VALIDATE_INT);
        if (false === $limit) {
            $limit = self::DEFAULT_UNRETRIEVED_TASK_ID_LIMIT;
        }

        if ($limit > self::MAX_UNRETRIEVED_TASK_ID_LIMIT) {
            $limit = self::MAX_UNRETRIEVED_TASK_ID_LIMIT;
        }

        $taskIds = $this->taskService->getUnretrievedRemoteTaskIds($testModel->getEntity(), $limit);

        return new JsonResponse($taskIds);
    }

    /**
     * @param Request $request
     * @param int $test_id
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retrieveAction(Request $request, int $test_id): Response
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        $remoteTaskIds = $this->getRequestRemoteTaskIds($request);
        $remoteTaskIds = $remoteTaskIds ?? $testModel->getTaskIds();

        $this->taskService->getCollection($testModel->getEntity(), $testModel->getState(), $remoteTaskIds);

        return new Response();
    }

    /**
     * @param Request $request
     *
     * @return array|null
     */
    private function getRequestRemoteTaskIds(Request $request)
    {
        $requestTaskIds = $request->request->get('remoteTaskIds');

        $taskIds = [];

        if (substr_count($requestTaskIds, ':')) {
            $rangeLimits = explode(':', $requestTaskIds);
            $start = (int)$rangeLimits[0];
            $end = (int)$rangeLimits[1];

            for ($i = $start; $i <= $end; $i++) {
                $taskIds[] = $i;
            }
        } else {
            $rawRequestTaskIds = explode(',', $requestTaskIds);

            foreach ($rawRequestTaskIds as $requestTaskId) {
                if (ctype_digit($requestTaskId)) {
                    $taskIds[] = (int)$requestTaskId;
                }
            }
        }

        return (count($taskIds) > 0) ? $taskIds : null;
    }
}
