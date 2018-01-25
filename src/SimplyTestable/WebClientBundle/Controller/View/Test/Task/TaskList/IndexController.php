<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Task\TaskList;

use SimplyTestable\WebClientBundle\Controller\View\Test\ViewController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Model\Task\Collection as TaskCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends ViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner
{
    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('', 400);
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     *
     * @throws WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $taskService = $this->container->get('simplytestable.services.taskservice');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);
        $taskIds = $this->getTaskIdsFromRequest($request);

        if (empty($taskIds)) {
            return new Response('');
        }

        $tasks = $taskService->getCollection($test, $taskIds);
        $taskCollection = new TaskCollection($tasks);

        if ($taskCollection->isEmpty()) {
            return new Response('');
        }

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_collection_hash' => $taskCollection->getHash(),
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'test' => $test,
            'tasks' => $tasks
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Task/TaskList/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }


    /**
     * @param Request $request
     *
     * @return int[]
     */
    private function getTaskIdsFromRequest(Request $request)
    {
        $taskIds = [];
        $requestTaskIds = $request->request->get('taskIds');

        if (!is_array($requestTaskIds)) {
            return [];
        }

        foreach ($requestTaskIds as $taskId) {
            if (is_int($taskId) || ctype_digit($taskId)) {
                $taskIds[] = (int)$taskId;
            }
        }

        return $taskIds;
    }
}
