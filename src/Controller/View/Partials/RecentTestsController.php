<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DecoratedTestListFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestListService;
use App\Services\RemoteTestService;
use App\Services\TaskService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RecentTestsController extends AbstractBaseViewController
{
    const LIMIT = 3;

    private $testService;
    private $remoteTestService;
    private $taskService;
    private $remoteTestListService;
    private $decoratedTestListFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        RemoteTestListService $remoteTestListService,
        DecoratedTestListFactory $decoratedTestListFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->remoteTestListService = $remoteTestListService;
        $this->decoratedTestListFactory = $decoratedTestListFactory;
    }

    /**
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        $remoteTestList = $this->remoteTestListService->getRecent(self::LIMIT);
        $decoratedTestList = $this->decoratedTestListFactory->create($remoteTestList);

        foreach ($decoratedTestList as $decoratedTest) {
            if ($decoratedTest->requiresRemoteTasks() && $decoratedTest->isSingleUrl()) {
                $this->taskService->getCollection($decoratedTest->getTest());
            }
        }

        return $this->render('Partials/Dashboard/recent-tests.html.twig', [
            'test_list' => $decoratedTestList,
        ]);
    }
}
