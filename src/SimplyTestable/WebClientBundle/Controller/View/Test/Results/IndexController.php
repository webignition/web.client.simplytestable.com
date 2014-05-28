<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    /**
     * @var Test
     */
    private $test;

    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test',
        ), array(
            ':bs3/Test',
        ), $viewName);
    }


    /**
     * @return Test
     */
    private function getTest() {
        if (is_null($this->test)) {
            $this->test = $this->getTestService()->get(
                $this->getRequest()->attributes->get('website'),
                $this->getRequest()->attributes->get('test_id')
            );
        }

        return $this->test;
    }


    public function indexAction($website, $test_id) {
        if ($this->getTest()->getState() == 'failed-no-sitemap') {
            return $this->redirect($this->generateUrl('view_test_results_failednourlsdetected_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if ($this->getTest()->getState() == 'rejected') {
            return $this->redirect($this->generateUrl('view_test_results_rejected_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if ($this->getTest()->getWebsite() != $website) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        if (!$this->hasRequestFilter()) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id,
                'filter' => $this->getDefaultRequestFilter()
            ), true));
        }

        $isOwner = $this->getTestService()->getRemoteTestService()->owns($this->getTest());

        $this->getTestOptionsAdapter()->setRequestData($this->getRemoteTest()->getOptions());
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();

        $remoteTaskIds = ($this->getRequestFilter() == 'all' && is_null($this->getRequestType()))
            ? null
            : $this->getFilteredTaskCollectionRemoteIds(
                $this->getTest(),
                $this->getRequestFilter(),
                $this->getRequestType()
            );

        $tasks = $this->getTaskService()->getCollection($this->getTest(), $remoteTaskIds);

        $viewData = array(
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'is_public' => $this->getTestService()->getRemoteTestService()->isPublic(),
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'remote_test' => $this->getRemoteTest(),
            'is_owner' => $isOwner,
            'type' => $this->getRequestType(),
            'type_label' => $this->getTaskTypeLabel($this->getRequestType()),
            'filter' => $this->getRequestFilter(),
            'filter_label' => ucwords(str_replace('-', ' ', $this->getRequestFilter())),
            'task_types' => $this->container->getParameter('task_types'),
            'test_options' => $testOptions->__toKeyArray(),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
            'tasks' => $this->getTasksGroupedByUrl($tasks),
            'filtered_task_counts' => $this->getFilteredTaskCounts($this->getTest(), $this->getRequestType()),
            'domain_test_count' => $this->getTestService()->getRemoteTestService()->getFinishedCount($this->getTest()->getWebsite()),
            'test_authentication_enabled' => $this->getRemoteTest()->hasParameter('http-auth-username'),
            'test_cookies_enabled' => $this->getRemoteTest()->hasParameter('cookies'),
            'test_cookies' => $this->getTestCookies(),
            'default_css_validation_options' => array(
                'ignore-warnings' => 1,
                'vendor-extensions' => 'warn',
                'ignore-common-cdns' => 1
            ),
            'default_js_static_analysis_options' => array(
                'ignore-common-cdns' => 1,
                'jslint-option-maxerr' => 50,
                'jslint-option-indent' => 4,
                'jslint-option-maxlen' => 256
            ),
        );

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return array(
            'rand' => rand()
        );
    }


    /**
     * @return bool|\SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest
     */
    private function getRemoteTest() {
        return $this->getTestService()->getRemoteTestService()->get();
    }


    /**
     * @return string|null
     */
    private function getRequestType() {
        if (!$this->getRequest()->query->has('type')) {
            return null;
        }

        $type = trim($this->getRequest()->query->get('type'));
        return ($type == '') ? null : $type;
    }


    /**
     * @return string
     */
    private function getRequestFilter() {
        if (!$this->getRequest()->query->has('filter')) {
            return $this->getDefaultRequestFilter();
        }

        $filter = trim($this->getRequest()->query->get('filter'));
        return ($filter == '') ? $this->getDefaultRequestFilter() : $filter;
    }


    /**
     * @return bool
     */
    private function hasRequestFilter() {
        return $this->getRequest()->query->has('filter');
    }


    /**
     * @return string
     */
    private function getDefaultRequestFilter() {
        if ($this->getTest()->hasErrors()) {
            return 'with-errors';
        }

        if ($this->getTest()->hasWarnings()) {
            return 'with-warnings';
        }

        return 'without-errors';
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');

            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');

            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getAvailableTaskTypes());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
            $this->testOptionsAdapter->setInvertInvertableOptions(true);

            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }
        }

        return $this->testOptionsAdapter;
    }


    /**
     *
     * @return array
     */
    private function getAvailableTaskTypes() {
        $this->getAvailableTaskTypeService()->setUser($this->getUser());
        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());

        return $this->getAvailableTaskTypeService()->get();
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\AvailableTaskTypeService
     */
    private function getAvailableTaskTypeService() {
        return $this->container->get('simplytestable.services.availabletasktypeservice');
    }


    /**
     *
     * @return array
     */
    private function getCssValidationCommonCdnsToIgnore() {
        if (!$this->container->hasParameter('css-validation-ignore-common-cdns')) {
            return array();
        }

        return $this->container->getParameter('css-validation-ignore-common-cdns');
    }


    /**
     *
     * @return array
     */
    private function getJsStaticAnalysisCommonCdnsToIgnore() {
        if (!$this->container->hasParameter('js-static-analysis-ignore-common-cdns')) {
            return array();
        }

        return $this->container->getParameter('js-static-analysis-ignore-common-cdns');
    }


    private function getTaskTypeLabel($taskTypeFilter) {
        if (is_null($taskTypeFilter)) {
            return 'All';
        }

        $taskTypeLabel = str_replace(
            array('css', 'html', 'js', 'link'),
            array('CSS', 'HTML', 'JS', 'Link'),
            $taskTypeFilter
        );

        return $taskTypeLabel;
    }


    private function getFilteredTaskCollectionRemoteIds(Test $test, $taskOutcomeFilter, $taskTypeFilter) {
        if ($taskTypeFilter == 'javascript static analysis') {
            $taskTypeFilter = 'js static analysis';
        }

        $this->getTaskCollectionFilterService()->setTest($test);
        $this->getTaskCollectionFilterService()->setOutcomeFilter($taskOutcomeFilter);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);

        return $this->getTaskCollectionFilterService()->getRemoteIds();
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService
     */
    private function getTaskCollectionFilterService() {
        return $this->container->get('simplytestable.services.taskcollectionfilterservice');
    }


    /**
     *
     * @param array $tasks
     * @return array
     */
    private function getTasksGroupedByUrl($tasks = array()) {
        $tasksGroupedByUrl = array();
        foreach ($tasks as $task) {
            $url = new \webignition\NormalisedUrl\NormalisedUrl($task->getUrl());
            $url->getConfiguration()->enableConvertIdnToUtf8();

            $url = (string)$url;

            /* @var $task Task */
            if (!isset($tasksGroupedByUrl[$url])) {
                $tasksGroupedByUrl[$url] = array();
            }

            $tasksGroupedByUrl[$url][] = $task;
        }

        return $tasksGroupedByUrl;
    }


    private function getFilteredTaskCounts(Test $test, $taskTypeFilter) {
        if ($taskTypeFilter == 'javascript static analysis') {
            $taskTypeFilter = 'js static analysis';
        }

        $this->getTaskCollectionFilterService()->setTest($test);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);

        $filteredTaskCounts = array();

        $this->getTaskCollectionFilterService()->setOutcomeFilter(null);
        $filteredTaskCounts['all'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $this->getTaskCollectionFilterService()->setOutcomeFilter('with-errors');
        $filteredTaskCounts['with_errors'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $this->getTaskCollectionFilterService()->setOutcomeFilter('with-warnings');
        $filteredTaskCounts['with_warnings'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $this->getTaskCollectionFilterService()->setOutcomeFilter('without-errors');
        $filteredTaskCounts['without_errors'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $this->getTaskCollectionFilterService()->setOutcomeFilter('skipped');
        $filteredTaskCounts['skipped'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $this->getTaskCollectionFilterService()->setOutcomeFilter('cancelled');
        $filteredTaskCounts['cancelled'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        return $filteredTaskCounts;
    }


    private function getTestCookies() {
        if ($this->getRemoteTest()->hasParameter('cookies')) {
            return $this->getRemoteTest()->getParameter('cookies');
        }

        return array(
            array(
                'name' => null,
                'value' => null
            )
        );
    }

}
