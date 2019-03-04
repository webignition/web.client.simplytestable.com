<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TaskService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RecentTestsController extends AbstractBaseViewController
{
    const LIMIT = 3;

    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var TaskService
     */
    private $taskService;


    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
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
        $testList = $this->remoteTestService->getRecent(self::LIMIT);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $test = $this->testService->get($remoteTest->getWebsite(), $remoteTest->getId());

            $testList->addTest($test);

            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->taskService->getCollection($test);
                }
            }
        }

        return $this->render('Partials/Dashboard/recent-tests.html.twig', [
            'test_list' => $testList,
        ]);
    }
}
