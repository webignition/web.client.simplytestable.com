<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
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
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $taskService = $this->container->get('SimplyTestable\WebClientBundle\Services\TaskService');

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

        return $this->render(
            'SimplyTestableWebClientBundle:bs3/Dashboard/Partial/RecentTests:index.html.twig',
            $viewData
        );
    }
}
