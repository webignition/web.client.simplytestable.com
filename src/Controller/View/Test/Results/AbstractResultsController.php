<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Model\Test as TestModel;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\SimplyTestableUserInterface\UserInterface;

abstract class AbstractResultsController extends AbstractBaseViewController
{
    private $remoteTestService;
    private $taskTypeService;
    private $testOptionsRequestAdapterFactory;
    private $cssValidationTestConfiguration;
    private $urlViewValues;
    private $userManager;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        RemoteTestService $remoteTestService,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->remoteTestService = $remoteTestService;
        $this->taskTypeService = $taskTypeService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->testRetriever = $testRetriever;
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

    protected function createTestOptions(TestModel $test): array
    {
        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData(new ParameterBag($test->getTaskOptions()));

        return $testOptionsAdapter->getTestOptions()->__toKeyArray();
    }

    protected function getCssValidationExcludedDomains(): array
    {
        return $this->cssValidationTestConfiguration->getExcludedDomains();
    }

    protected function createWebsiteViewValues(string $website): array
    {
        return $this->urlViewValues->create($website);
    }

    protected function getUser(): UserInterface
    {
        return $this->userManager->getUser();
    }

    protected function retrieveTest(int $testId): TestModel
    {
        return $this->testRetriever->retrieve($testId);
    }
}
