<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Task\TaskList;

use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\Task\Collection as TaskCollection;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractRequiresValidOwnerController implements RequiresValidUser
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
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $testService = $this->container->get(TestService::class);
        $taskService = $this->container->get(TaskService::class);
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $templating = $this->container->get('templating');

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
