<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Model\Test\DecoratedTest;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\TestFactory;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestFinishedSummaryController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    private $testFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TestFactory $testFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->testFactory = $testFactory;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $test = $this->testService->get($test_id);
        $remoteTest = $this->remoteTestService->get($test->getTestId());

        $testModel = $this->testFactory->create($test, $remoteTest, [
            'website' => $remoteTest->getWebsite(),
            'user' => $remoteTest->getUser(),
            'state' => $remoteTest->getState(),
            'type' => $remoteTest->getType(),
            'url_count' => $remoteTest->getUrlCount(),
            'task_count' => $remoteTest->getTaskCount(),
            'errored_task_count' => $remoteTest->getErroredTaskCount(),
            'cancelled_task_count' => $remoteTest->getCancelledTaskCount(),
            'parameters' => $remoteTest->getEncodedParameters(),
            'amendments' => $remoteTest->getAmmendments(),
            'crawl' => $remoteTest->getCrawl(),
            'task_types' => $remoteTest->getTaskTypes(),
        ]);
        $decoratedTest = new DecoratedTest($testModel);

        return $this->renderWithDefaultViewParameters(
            'Partials/Test/Summary/finished.html.twig',
            [
                'test' => $decoratedTest,
            ],
            $response
        );
    }
}
