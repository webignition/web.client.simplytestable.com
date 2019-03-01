<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class PreparingStatsController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
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
        $test = $this->testService->get($website, $test_id);

        $completionPercent = 0;
        $remainingTasksToRetrieveCount = 0;
        $localTaskCount = 0;
        $remoteTaskCount = 0;

        $remoteTest = $this->remoteTestService->get();

        if (!empty($remoteTest)) {
            $localTaskCount = $test->getTaskCount();
            $remoteTaskCount = $remoteTest->getTaskCount();

            $completionPercent = 0 === $remoteTaskCount
                ? 100
                : round(($localTaskCount / $remoteTaskCount) * 100);

            $remainingTasksToRetrieveCount = $remoteTaskCount - $localTaskCount;
        }

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
