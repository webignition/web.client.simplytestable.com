<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends BaseViewController implements RequiresValidUser
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskService $taskService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $pageNumber = (int)$request->attributes->get('page_number');

        if ($pageNumber < self::DEFAULT_PAGE_NUMBER) {
            return new RedirectResponse($this->generateUrl('view_test_history_index_index'));
        }

        $filter = trim($request->get('filter'));
        if (empty($filter)) {
            $filter = null;
        }

        $testListOffset = ($pageNumber - 1) * self::TEST_LIST_LIMIT;

        $testList = $this->remoteTestService->getFinished(self::TEST_LIST_LIMIT, $testListOffset, $filter);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $this->remoteTestService->set($remoteTest);
            $test = $this->testService->get($remoteTest->getWebsite(), $remoteTest->getId());

            $testList->addTest($test);

            if ($testList->requiresResults($test) && $remoteTest->isSingleUrl()) {
                $this->taskService->getCollection($test);
            }
        }

        $isPageNumberAboveRange = $pageNumber > $testList->getPageCount() && $testList->getPageCount() > 0;

        if ($isPageNumberAboveRange) {
            return new RedirectResponse($this->generateUrl(
                'app_history',
                [
                    'page_number' => $testList->getPageCount(),
                    'filter' => $filter,
                ]
            ));
        }

        $response = $this->cacheValidator->createResponse($request, [
            'test_list_hash' => $testList->getHash(),
            'filter' => $filter,
            'page_number' => $pageNumber
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $websitesSourceUrl = $this->generateUrl('view_test_history_websitelist_index');

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
