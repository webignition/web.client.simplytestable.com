<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskService $taskService
     */
    public function __construct(
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService
    ) {
        parent::__construct($twig, $defaultViewParameters);

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

        $viewData = [
            'test_list' => $testList,
        ];

        $content = $this->twig->render(
            'SimplyTestableWebClientBundle:bs3/Dashboard/Partial/RecentTests:index.html.twig',
            $viewData
        );

        return new Response($content);
    }
}
