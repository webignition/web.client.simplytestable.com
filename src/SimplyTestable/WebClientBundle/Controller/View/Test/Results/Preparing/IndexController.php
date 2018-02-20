<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Preparing;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends BaseViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner
{
    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $cacheValidatorService = $this->container->get('SimplyTestable\WebClientBundle\Services\CacheValidatorService');
        $router = $this->container->get('router');
        $taskService = $this->container->get(TaskService::class);
        $templating = $this->container->get('templating');
        $urlViewValuesService = $this->container->get(UrlViewValuesService::class);

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $localTaskCount = $test->getTaskCount();
        $remoteTaskCount = $remoteTest->getTaskCount();

        if (0 === $remoteTaskCount) {
            return new RedirectResponse($router->generate(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $completionPercent = (int)round(($localTaskCount / $remoteTaskCount) * 100);
        $tasksToRetrieveCount = $remoteTaskCount - $localTaskCount;

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        if (!$testService->isFinished($test)) {
            return new RedirectResponse($router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        if ($test->getWebsite() != $website) {
            return new RedirectResponse($router->generate(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        if (!$test->hasTaskIds()) {
            $taskService->getRemoteTaskIds($test);
        }

        $viewData = [
            'completion_percent' => $completionPercent,
            'website' => $urlViewValuesService->create($website),
            'test' => $test,
            'local_task_count' => $test->getTaskCount(),
            'remote_task_count' => $remoteTest->getTaskCount(),
            'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/Preparing/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('', 400);
    }
}
