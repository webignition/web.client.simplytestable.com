<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends AbstractResultsController
{
    const FILTER_WITH_ERRORS = 'with-errors';
    const FILTER_WITH_WARNINGS = 'with-warnings';
    const FILTER_WITHOUT_ERRORS = 'without-errors';
    const FILTER_ALL = 'all';
    const FILTER_SKIPPED = 'skipped';
    const FILTER_CANCELLED = 'cancelled';

    /**
     * @var string[]
     */
    private $filters = [
        self::FILTER_WITH_ERRORS,
        self::FILTER_WITH_WARNINGS,
        self::FILTER_WITHOUT_ERRORS,
        self::FILTER_ALL,
        self::FILTER_SKIPPED,
        self::FILTER_CANCELLED,
    ];

    /**
     * {@inheritdoc}
     */
    public function getRequestWebsiteMismatchResponse(Request $request)
    {
        $router = $this->container->get('router');

        return new RedirectResponse($router->generate(
            'app_test_redirector',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
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
        $router = $this->container->get('router');
        $testService = $this->container->get('SimplyTestable\WebClientBundle\Services\TestService');
        $remoteTestService = $this->container->get('SimplyTestable\WebClientBundle\Services\RemoteTestService');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');
        $taskService = $this->container->get('simplytestable.services.taskservice');
        $taskCollectionFilterService = $this->container->get('simplytestable.services.taskcollectionfilterservice');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $templating = $this->container->get('templating');
        $testOptionsAdapterFactory = $this->container->get('simplytestable.services.testoptions.adapter.factory');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $taskTypeService->setUser($user);
        if (!SystemUserService::isPublicUser($user)) {
            $taskTypeService->setUserIsAuthenticated();
        }

        if ($this->requiresPreparation($remoteTest, $test)) {
            return new RedirectResponse($router->generate(
                'view_test_results_preparing_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $taskService->getCollection($test);

        $filter = trim($request->query->get('filter'));
        $taskType = trim($request->query->get('type'));
        $defaultFilter = $this->getDefaultRequestFilter($test);

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setTypeFilter($taskType);

        $filteredTaskCounts = $this->createFilteredTaskCounts($taskCollectionFilterService);

        if (!$this->isFilterValid($filter, $filteredTaskCounts)) {
            return new RedirectResponse($router->generate(
                'view_test_results_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                    'filter' => $defaultFilter
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'type' => $taskType,
            'filter' => $filter,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $remoteTaskIds = $this->getRemoteTaskIds(
            $taskCollectionFilterService,
            $filter,
            $taskType
        );

        $tasks = $taskService->getCollection($test, $remoteTaskIds);

        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($remoteTest->getOptions());
        $testOptionsAdapter->setInvertInvertableOptions(true);

        $isOwner = $remoteTestService->owns($user);

        $viewData = [
            'website' => $urlViewValuesService->create($website),
            'test' => $test,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'remote_test' => $remoteTest,
            'is_owner' => $isOwner,
            'type' => $taskType,
            'type_label' => $this->getTaskTypeLabel($taskType),
            'filter' => $filter,
            'filter_label' => ucwords(str_replace('-', ' ', $filter)),
            'available_task_types' => $this->getAvailableTaskTypes($isOwner),
            'task_types' => $taskTypeService->get(),
            'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
            'css_validation_ignore_common_cdns' =>
                $this->container->getParameter('css-validation-ignore-common-cdns'),
            'js_static_analysis_ignore_common_cdns' =>
                $this->container->getParameter('js-static-analysis-ignore-common-cdns'),
            'tasks' => $tasks,
            'filtered_task_counts' => $filteredTaskCounts,
            'domain_test_count' => $remoteTestService->getFinishedCount($website),
            'default_css_validation_options' => [
                'ignore-warnings' => 1,
                'vendor-extensions' => 'warn',
                'ignore-common-cdns' => 1
            ],
            'default_js_static_analysis_options' => [
                'ignore-common-cdns' => 1,
                'jslint-option-maxerr' => 50,
                'jslint-option-indent' => 4,
                'jslint-option-maxlen' => 256
            ],
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @param string $filter
     * @param array $filteredTaskCounts
     *
     * @return bool
     */
    private function isFilterValid($filter, array $filteredTaskCounts)
    {
        if (!in_array($filter, $this->filters)) {
            return false;
        }

        $modifiedFilter = str_replace('-', '_', $filter);

        return $filteredTaskCounts[$modifiedFilter] > 0;
    }


    /**
     * @param Test $test
     *
     * @return string
     */
    private function getDefaultRequestFilter(Test $test)
    {
        if ($test->hasErrors()) {
            return 'with-errors';
        }

        if ($test->hasWarnings()) {
            return 'with-warnings';
        }

        return 'without-errors';
    }

    /**
     * @param $taskType
     *
     * @return string
     */
    private function getTaskTypeLabel($taskType)
    {
        if (empty($taskType)) {
            return 'All';
        }

        $taskTypeLabel = str_replace(
            ['css', 'html', 'js', 'link'],
            ['CSS', 'HTML', 'JS', 'Link'],
            $taskType
        );

        return $taskTypeLabel;
    }

    /**
     * @param TaskCollectionFilterService $taskCollectionFilterService
     *
     * @return array
     */
    private function createFilteredTaskCounts(TaskCollectionFilterService $taskCollectionFilterService)
    {
        $filteredTaskCounts = [];

        $taskCollectionFilterService->setOutcomeFilter(null);
        $filteredTaskCounts['all'] = $taskCollectionFilterService->getRemoteIdCount();

        $filters = [
            self::FILTER_WITH_ERRORS,
            self::FILTER_WITH_WARNINGS,
            self::FILTER_WITHOUT_ERRORS,
            self::FILTER_SKIPPED,
            self::FILTER_CANCELLED,
        ];

        foreach ($filters as $filter) {
            $taskCollectionFilterService->setOutcomeFilter($filter);

            $filteredTaskCountKey = str_replace('-', '_', $filter);
            $filteredTaskCounts[$filteredTaskCountKey] = $taskCollectionFilterService->getRemoteIdCount();
        }

        return $filteredTaskCounts;
    }

    /**
     * @param TaskCollectionFilterService $taskCollectionFilterService
     * @param string $filter
     * @param string $taskType
     *
     * @return int[]|null
     */
    private function getRemoteTaskIds(TaskCollectionFilterService $taskCollectionFilterService, $filter, $taskType)
    {
        if ($filter == 'all' && empty($taskType)) {
            return null;
        }

        $taskCollectionFilterService->setOutcomeFilter($filter);
        $taskCollectionFilterService->setTypeFilter($taskType);

        return $taskCollectionFilterService->getRemoteIds();
    }

    /**
     * @param bool $isOwner
     *
     * @return array
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    private function getAvailableTaskTypes($isOwner)
    {
        $remoteTestService = $this->container->get('SimplyTestable\WebClientBundle\Services\RemoteTestService');
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');

        $remoteTest = $remoteTestService->get();

        if ($remoteTest->getIsPublic() && !$isOwner) {
            $availableTaskTypes = $taskTypeService->get();
            $remoteTestTaskTypes = $remoteTest->getTaskTypes();

            foreach ($availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                if (!in_array($taskTypeDetails['name'], $remoteTestTaskTypes)) {
                    unset($availableTaskTypes[$taskTypeKey]);
                }
            }

            return $availableTaskTypes;
        }

        return $taskTypeService->getAvailable();
    }
}
