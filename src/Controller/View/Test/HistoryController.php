<?php

namespace App\Controller\View\Test;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DecoratedTestListFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestListService;
use App\Services\RemoteTestService;
use App\Services\TaskService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class HistoryController extends AbstractBaseViewController
{
    const RESULTS_PREPARATION_THRESHOLD = 10;
    const DEFAULT_PAGE_NUMBER = 1;
    const TEST_LIST_LIMIT = 10;

    private $testService;
    private $remoteTestService;
    private $taskService;
    private $remoteTestListService;
    private $decoratedTestListFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        RemoteTestListService $remoteTestListService,
        DecoratedTestListFactory $decoratedTestListFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->remoteTestListService = $remoteTestListService;
        $this->decoratedTestListFactory = $decoratedTestListFactory;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request)
    {
        $pageNumber = (int)$request->attributes->get('page_number');

        if ($pageNumber < self::DEFAULT_PAGE_NUMBER) {
            return new RedirectResponse($this->generateUrl('view_test_history_page1'));
        }

        $filter = trim($request->get('filter'));
        if (empty($filter)) {
            $filter = null;
        }

        $testListOffset = ($pageNumber - 1) * self::TEST_LIST_LIMIT;

        $remoteTestList = $this->remoteTestListService->getFinished(self::TEST_LIST_LIMIT, $testListOffset, $filter);
        $decoratedTestList = $this->decoratedTestListFactory->create($remoteTestList);

        foreach ($decoratedTestList as $decoratedTest) {
            if ($decoratedTest->requiresRemoteTasks() && $decoratedTest->isSingleUrl()) {
                $test = $decoratedTest->getEntity();
                $this->taskService->getCollection($test, $test->getTaskIds());
            }
        }

        $isPageNumberAboveRange =
            $pageNumber > $decoratedTestList->getPageCount() && $decoratedTestList->getPageCount() > 0;

        if ($isPageNumberAboveRange) {
            return new RedirectResponse($this->generateUrl(
                'view_test_history',
                [
                    'page_number' => $decoratedTestList->getPageCount(),
                    'filter' => $filter,
                ]
            ));
        }

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'test_list_hash' => $decoratedTestList->getHash(),
            'filter' => $filter,
            'page_number' => $pageNumber
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $websitesSourceUrl = $this->generateUrl('view_website_list');

        return $this->renderWithDefaultViewParameters(
            'test-history.html.twig',
            [
                'test_list' => $decoratedTestList,
                'pagination_page_numbers' => $decoratedTestList->getPageNumbers(),
                'filter' => $filter,
                'websites_source' => $websitesSourceUrl
            ],
            $response
        );
    }
}
