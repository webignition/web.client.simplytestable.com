<?php

namespace AppBundle\Controller\View\Dashboard\Partial;

use AppBundle\Controller\BaseViewController;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Interfaces\Controller\RequiresValidUser;
use AppBundle\Model\RemoteTest\RemoteTest;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\RemoteTestService;
use AppBundle\Services\TaskService;
use AppBundle\Services\TestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RecentTestsController extends BaseViewController implements RequiresValidUser
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskService $taskService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $testList = $this->remoteTestService->getRecent(self::LIMIT);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $this->remoteTestService->set($remoteTest);
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