<?php

namespace App\Controller\View\Test\Results;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\TestRetriever;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreparingStatsController
{
    private $testRetriever;

    public function __construct(TestRetriever $testRetriever)
    {
        $this->testRetriever = $testRetriever;
    }

    /**
     * @param int $test_id
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function indexAction(int $test_id): JsonResponse
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        $localTaskCount = $testModel->getLocalTaskCount();
        $remoteTaskCount = $testModel->getRemoteTaskCount();

        $completionPercent = 0 === $remoteTaskCount
            ? 100
            : round(($localTaskCount / $remoteTaskCount) * 100);

        return new JsonResponse([
            'id' => $test_id,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remoteTaskCount - $localTaskCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTaskCount
        ]);
    }
}
