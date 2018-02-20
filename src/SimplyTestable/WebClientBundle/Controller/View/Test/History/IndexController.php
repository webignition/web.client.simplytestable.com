<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends BaseViewController implements IEFiltered, RequiresValidUser
{
    const RESULTS_PREPARATION_THRESHOLD = 10;
    const DEFAULT_PAGE_NUMBER = 1;
    const TEST_LIST_LIMIT = 10;

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
        $router = $this->container->get('router');
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);

        $pageNumber = (int)$request->attributes->get('page_number');

        if ($pageNumber < self::DEFAULT_PAGE_NUMBER) {
            return new RedirectResponse($router->generate(
                'view_test_history_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $filter = trim($request->get('filter'));
        if (empty($filter)) {
            $filter = null;
        }

        $testList = $this->getFinishedTests(
            self::TEST_LIST_LIMIT,
            ($pageNumber - 1) * self::TEST_LIST_LIMIT,
            $filter
        );

        $isPageNumberAboveRange = $pageNumber > $testList->getPageCount() && $testList->getPageCount() > 0;

        if ($isPageNumberAboveRange) {
            return new RedirectResponse($router->generate(
                'app_history',
                [
                    'page_number' => $testList->getPageCount(),
                    'filter' => $filter,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $response = $cacheValidatorService->createResponse($request, [
            'test_list_hash' => $testList->getHash(),
            'filter' => $filter,
            'page_number' => $pageNumber
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $websitesSourceUrl = $router->generate(
            'view_test_history_websitelist_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $viewData = [
            'test_list' => $testList,
            'pagination_page_numbers' => $testList->getPageNumbers(),
            'filter' => $filter,
            'websites_source' => $websitesSourceUrl
        ];

        $templating = $this->container->get('templating');

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/History/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $filter
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function getFinishedTests($limit, $offset, $filter = null)
    {
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $taskService = $this->container->get(TaskService::class);

        $testList = $remoteTestService->getFinished($limit, $offset, $filter);

        foreach ($testList->get() as $testObject) {
            /* @var RemoteTest $remoteTest */
            $remoteTest = $testObject['remote_test'];

            $remoteTestService->set($remoteTest);
            $test = $testService->get($remoteTest->getWebsite(), $remoteTest->getId());

            $testList->addTest($test);

            if ($testList->requiresResults($test) && $remoteTest->isSingleUrl()) {
                $taskService->getCollection($test);
            }
        }

        return $testList;
    }
}
