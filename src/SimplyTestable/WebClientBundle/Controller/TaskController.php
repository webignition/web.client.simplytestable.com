<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;

class TaskController extends TestViewController implements RequiresValidOwner
{
    const DEFAULT_UNRETRIEVED_TASKID_LIMIT = 100;
    const MAX_UNRETRIEVED_TASKID_LIMIT = 1000;

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new JsonResponse();
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return JsonResponse
     *
     * @throws WebResourceException
     */
    public function idCollectionAction($website, $test_id)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $taskService = $this->container->get('simplytestable.services.taskservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);

        $taskIds = $taskService->getRemoteTaskIds($test);

        return new JsonResponse($taskIds);
    }

    /**
     * @param string $website
     * @param int $test_id
     * @param int|null $limit
     *
     * @return JsonResponse
     *
     * @throws WebResourceException
     */
    public function unretrievedIdCollectionAction($website, $test_id, $limit = null)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $taskService = $this->container->get('simplytestable.services.taskservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);

        $limit = filter_var($limit, FILTER_VALIDATE_INT);
        if (false === $limit) {
            $limit = self::DEFAULT_UNRETRIEVED_TASKID_LIMIT;
        }

        if ($limit > self::MAX_UNRETRIEVED_TASKID_LIMIT) {
            $limit = self::MAX_UNRETRIEVED_TASKID_LIMIT;
        }

        $taskIds = $taskService->getUnretrievedRemoteTaskIds($test, $limit);

        return new JsonResponse($taskIds);
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
    public function retrieveAction(Request $request, $website, $test_id)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $taskService = $this->container->get('simplytestable.services.taskservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);
        $taskService->setUser($user);

        $test = $testService->get($website, $test_id);

        $taskService->getCollection($test, $this->getRequestRemoteTaskIds($request));

        return new Response();
    }

    /**
     * @param Request $request
     *
     * @return array|null
     */
    private function getRequestRemoteTaskIds(Request $request)
    {
        $requestTaskIds = $request->request->get('remoteTaskIds');

        $taskIds = [];

        if (substr_count($requestTaskIds, ':')) {
            $rangeLimits = explode(':', $requestTaskIds);
            $start = (int)$rangeLimits[0];
            $end = (int)$rangeLimits[1];

            for ($i = $start; $i <= $end; $i++) {
                $taskIds[] = $i;
            }
        } else {
            $rawRequestTaskIds = explode(',', $requestTaskIds);

            foreach ($rawRequestTaskIds as $requestTaskId) {
                if (ctype_digit($requestTaskId)) {
                    $taskIds[] = (int)$requestTaskId;
                }
            }
        }

        return (count($taskIds) > 0) ? $taskIds : null;
    }
}
