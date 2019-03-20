<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class PreparingController extends AbstractBaseViewController
{
    private $urlViewValues;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->urlViewValues = $urlViewValues;
        $this->testRetriever = $testRetriever;
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
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        $remoteTaskCount = $testModel->getRemoteTaskCount();

        if (0 === $remoteTaskCount) {
            $routeName = $testModel->isFinished()
                ? 'view_test_results'
                : 'view_test_progress';

            return new RedirectResponse($this->generateUrl(
                $routeName,
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        $localTaskCount = $testModel->getLocalTaskCount();
        $completionPercent = (int)round(($localTaskCount / $remoteTaskCount) * 100);
        $tasksToRetrieveCount = $remoteTaskCount - $localTaskCount;

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'completion_percent' => $completionPercent,
            'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        if (!$testModel->isFinished()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing',
                [
                    'test_id' => $test_id,
                    'website' => $testModel->getWebsite(),
                ]
            ));
        }

        return $this->renderWithDefaultViewParameters(
            'test-results-preparing.html.twig',
            [
                'completion_percent' => $completionPercent,
                'website' => $this->urlViewValues->create($website),
                'test' => $testModel,
                'local_task_count' => $testModel->getLocalTaskCount(),
                'remote_task_count' => $testModel->getRemoteTaskCount(),
                'remaining_tasks_to_retrieve_count' => $tasksToRetrieveCount,
            ],
            $response
        );
    }
}
