<?php

namespace App\Controller\View\Test;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Services\CacheableResponseFactory;
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

    private $remoteTestListService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        RemoteTestListService $remoteTestListService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->remoteTestListService = $remoteTestListService;
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

        $testList = $this->remoteTestListService->getFinished(self::TEST_LIST_LIMIT, $testListOffset, $filter);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $test = $this->testService->get($remoteTest->getWebsite(), $remoteTest->getId());

            $testList->addTest($test);

            if ($testList->requiresResults($test) && $remoteTest->isSingleUrl()) {
                $this->taskService->getCollection($test);
            }
        }

        $isPageNumberAboveRange = $pageNumber > $testList->getPageCount() && $testList->getPageCount() > 0;

        if ($isPageNumberAboveRange) {
            return new RedirectResponse($this->generateUrl(
                'view_test_history',
                [
                    'page_number' => $testList->getPageCount(),
                    'filter' => $filter,
                ]
            ));
        }

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'test_list_hash' => $testList->getHash(),
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
                'test_list' => $testList,
                'pagination_page_numbers' => $testList->getPageNumbers(),
                'filter' => $filter,
                'websites_source' => $websitesSourceUrl
            ],
            $response
        );
    }
}
