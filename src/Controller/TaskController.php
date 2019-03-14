<?php

namespace App\Controller;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\TaskService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    const DEFAULT_UNRETRIEVED_TASKID_LIMIT = 100;
    const MAX_UNRETRIEVED_TASKID_LIMIT = 1000;

    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @param TestService $testService
     * @param TaskService $taskService
     */
    public function __construct(TestService $testService, TaskService $taskService)
    {
        $this->testService = $testService;
        $this->taskService = $taskService;
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function idCollectionAction(string $website, int $test_id): JsonResponse
    {
        $test = $this->testService->get($website, $test_id);

        return new JsonResponse($test->getTaskIds());
    }

    /**
     * @param string $website
     * @param int $test_id
     * @param int|null $limit
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function unretrievedIdCollectionAction(string $website, int $test_id, ?int $limit = null): JsonResponse
    {
        $test = $this->testService->get($website, $test_id);

        $limit = filter_var($limit, FILTER_VALIDATE_INT);
        if (false === $limit) {
            $limit = self::DEFAULT_UNRETRIEVED_TASKID_LIMIT;
        }

        if ($limit > self::MAX_UNRETRIEVED_TASKID_LIMIT) {
            $limit = self::MAX_UNRETRIEVED_TASKID_LIMIT;
        }

        $taskIds = $this->taskService->getUnretrievedRemoteTaskIds($test, $limit);

        return new JsonResponse($taskIds);
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
    public function retrieveAction(Request $request, string $website, int $test_id): Response
    {
        $test = $this->testService->get($website, $test_id);

        $remoteTaskIds = $this->getRequestRemoteTaskIds($request);
        $remoteTaskIds = $remoteTaskIds ?? $test->getTaskIds();

        $this->taskService->getCollection($test, $remoteTaskIds);

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
