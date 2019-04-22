<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TaskTypeService;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractResultsController extends AbstractBaseViewController
{
    private $remoteTestService;
    private $taskTypeService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        RemoteTestService $remoteTestService,
        TaskTypeService $taskTypeService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->remoteTestService = $remoteTestService;
        $this->taskTypeService = $taskTypeService;
    }

    protected function getDomainTestCount(string $website): int
    {
        return $this->remoteTestService->getFinishedCount($website);
    }

    protected function getTaskTypes(): array
    {
        return $this->taskTypeService->get();
    }

    protected function getAvailableTaskTypes(
        array $testTaskTypes,
        bool $isPublic,
        bool $isOwner
    ): array {
        $allTaskTypes = $this->getTaskTypes();

        if ($isPublic && !$isOwner) {
            $availableTaskTypes = $allTaskTypes;

            foreach ($availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                if (!in_array($taskTypeDetails['name'], $testTaskTypes)) {
                    unset($availableTaskTypes[$taskTypeKey]);
                }
            }

            return $availableTaskTypes;
        }

        return $this->taskTypeService->getAvailable();
    }
}
