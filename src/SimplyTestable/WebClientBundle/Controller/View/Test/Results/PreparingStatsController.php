<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PreparingStatsController extends BaseViewController implements RequiresValidUser, RequiresValidOwner
{
    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return $this->createJsonResponse(0, 0, 0, 0, 0);
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction($website, $test_id)
    {
        $testService = $this->container->get('SimplyTestable\WebClientBundle\Services\TestService');
        $remoteTestService = $this->container->get('SimplyTestable\WebClientBundle\Services\RemoteTestService');

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $localTaskCount = $test->getTaskCount();
        $remoteTaskCount = $remoteTest->getTaskCount();

        $completionPercent = 0 === $remoteTaskCount
            ? 100
            : round(($localTaskCount / $remoteTaskCount) * 100);

        $remainingTasksToRetrieveCount = $remoteTaskCount - $localTaskCount;

        return $this->createJsonResponse(
            $test_id,
            $completionPercent,
            $remainingTasksToRetrieveCount,
            $localTaskCount,
            $remoteTaskCount
        );
    }

    /**
     * @param int $testId
     * @param int $completionPercent
     * @param int $remainingTasksToRetrieveCount
     * @param int $localTaskCount
     * @param int $remoteTaskCount
     *
     * @return JsonResponse
     */
    private function createJsonResponse(
        $testId,
        $completionPercent,
        $remainingTasksToRetrieveCount,
        $localTaskCount,
        $remoteTaskCount
    ) {
        return new JsonResponse([
            'id' => $testId,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $remainingTasksToRetrieveCount,
            'local_task_count' => $localTaskCount,
            'remote_task_count' => $remoteTaskCount
        ]);
    }
}
