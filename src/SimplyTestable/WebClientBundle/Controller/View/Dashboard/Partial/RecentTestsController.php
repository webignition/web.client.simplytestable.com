<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\Response;

class RecentTestsController extends BaseViewController implements RequiresValidUser
{
    const LIMIT = 3;

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

        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $taskService = $this->container->get(TaskService::class);
        $templating = $this->container->get('templating');

        $testList = $remoteTestService->getRecent(self::LIMIT);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $remoteTestService->set($remoteTest);
            $test = $testService->get($remoteTest->getWebsite(), $remoteTest->getId());

            $testList->addTest($test);

            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $taskService->getCollection($test);
                }
            }
        }

        $viewData = [
            'test_list' => $testList,
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Dashboard/Partial/RecentTests:index.html.twig',
            $viewData
        );

        return new Response($content);
    }
}
