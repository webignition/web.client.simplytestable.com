<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\Response;

class RecentTestsController extends BaseViewController implements RequiresValidUser
{
    const LIMIT = 3;

    /**
     * @return Response
     *
     * @throws WebResourceException
     */
    public function indexAction()
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $taskService = $this->container->get('simplytestable.services.taskservice');
        $userService = $this->container->get('simplytestable.services.userservice');

        $remoteTestService->setUser($userService->getUser());

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
