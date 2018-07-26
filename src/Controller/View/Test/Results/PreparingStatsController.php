<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheValidatorService;
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

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
        $remoteTest = $this->remoteTestService->get();

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
