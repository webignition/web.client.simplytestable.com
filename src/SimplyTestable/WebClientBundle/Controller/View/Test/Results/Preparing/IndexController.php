<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Preparing;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends BaseViewController implements RequiresValidUser, RequiresValidOwner
{
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
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskService $taskService
     * @param UrlViewValuesService $urlViewValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        UrlViewValuesService $urlViewValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->urlViewValues = $urlViewValues;
    }

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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $localTaskCount = $test->getTaskCount();
        $remoteTaskCount = $remoteTest->getTaskCount();

        if (0 === $remoteTaskCount) {
            return new RedirectResponse($this->generateUrl(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id,
                ]
            ));
        }

        $completionPercent = (int)round(($localTaskCount / $remoteTaskCount) * 100);
        $tasksToRetrieveCount = $remoteTaskCount - $localTaskCount;

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        if (!$this->testService->isFinished($test)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        if ($test->getWebsite() != $website) {
            return new RedirectResponse($this->generateUrl(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id,
                ]
            ));
        }

        if (!$test->hasTaskIds()) {
            $this->taskService->getRemoteTaskIds($test);
        }

        return $this->renderWithDefaultViewParameters(
            'SimplyTestableWebClientBundle:bs3/Test/Results/Preparing/Index:index.html.twig',
            [
                'completion_percent' => $completionPercent,
                'website' => $this->urlViewValues->create($website),
                'test' => $test,
                'local_task_count' => $test->getTaskCount(),
                'remote_task_count' => $remoteTest->getTaskCount(),
                'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
            ],
            $response
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('', 400);
    }
}
