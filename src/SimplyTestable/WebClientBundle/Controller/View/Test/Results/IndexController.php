<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends ResultsController {

    private $filters = array(
        'with-errors',
        'with-warnings',
        'without-errors',
        'all',
        'skipped',
        'cancelled',
    );

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test',
            'verbose.html.twig'
        ), array(
            ':bs3/Test',
            'index.html.twig'
        ), $viewName);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestWebsiteMismatchResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('app_test_redirector', array(
            'website' => $request->attributes->get('website'),
            'test_id' => $request->attributes->get('test_id')
        ), true));
    }


    public function indexAction($website, $test_id) {
        if ($this->requiresPreparation()) {
            return $this->getPreparationRedirectResponse();
        }

        if ($this->getRawRequestFilter() != $this->getRequestFilter()) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id,
                'filter' => $this->getDefaultRequestFilter()
            ), true));
        }

        $this->getTaskService()->getCollection($this->getTest());
        $tasks = $this->getTaskService()->getCollection($this->getTest(), $this->getRemoteTaskIds());

        $this->getTestOptionsAdapter()->setRequestData($this->getRemoteTest()->getOptions());

        $viewData = array(
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'is_public' => $this->getTestService()->getRemoteTestService()->isPublic(),
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'remote_test' => $this->getRemoteTest(),
            'is_owner' => $this->getTestService()->getRemoteTestService()->owns($this->getTest()),
            'type' => $this->getRequestType(),
            'type_label' => $this->getTaskTypeLabel($this->getRequestType()),
            'filter' => $this->getRequestFilter(),
            'filter_label' => ucwords(str_replace('-', ' ', $this->getRequestFilter())),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'task_types' => $this->getTaskTypeService()->get(),
            'test_options' => $this->getTestOptionsAdapter()->getTestOptions()->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
            'tasks' => $tasks,
            'filtered_task_counts' => $this->getFilteredTaskCounts(),
            'domain_test_count' => $this->getTestService()->getRemoteTestService()->getFinishedCount($this->getTest()->getWebsite()),
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
        $test = $this->getTest();

        return array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'is_public' => $this->getTestService()->getRemoteTestService()->isPublic(),
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'type' => $this->getRequestType(),
            'filter' => $this->getRequestFilter(),
        );
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

        if (!$this->isRelevantRequestFilter($filter)) {
            return $this->getDefaultRequestFilter();
        }

        return $filter;
    }


    /**
     * @return string|null
     */
    private function getRawRequestFilter() {
        return $this->getRequest()->query->get('filter');
    }


    /**
     * @param $filter
     * @return bool
     */
    private function isRelevantRequestFilter($filter) {
        if (!in_array($filter, $this->filters)) {
            return false;
        }

        $modifiedFilter = str_replace('-', '_', $filter);

        if (!array_key_exists($modifiedFilter, $this->getFilteredTaskCounts())) {
            return false;
        }

        return $this->getFilteredTaskCounts()[$modifiedFilter] > 0;
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


    /**
     * @return string
     */
    private function getNormalisedRequestType() {
        $type = $this->getRequestType();

        if ($type == 'javascript static analysis') {
            $type = 'js static analysis';
        }

        return $type;
    }


    private function getFilteredTaskCounts() {
        $this->getTaskCollectionFilterService()->setTypeFilter($this->getNormalisedRequestType());

        $filteredTaskCounts = array();

        $this->getTaskCollectionFilterService()->setOutcomeFilter(null);
        $filteredTaskCounts['all'] = $this->getTaskCollectionFilterService()->getRemoteIdCount();

        $filteredTaskCounts['with_errors'] = $this->getFilteredTaskCount('with-errors');
        $filteredTaskCounts['with_warnings'] = $this->getFilteredTaskCount('with-warnings');
        $filteredTaskCounts['without_errors'] = $this->getFilteredTaskCount('without-errors');
        $filteredTaskCounts['skipped'] = $this->getFilteredTaskCount('skipped');
        $filteredTaskCounts['cancelled'] = $this->getFilteredTaskCount('cancelled');

        return $filteredTaskCounts;
    }


    private function getFilteredTaskCount($outcomeFilter) {
        $this->getTaskCollectionFilterService()->setTypeFilter($this->getNormalisedRequestType());
        $this->getTaskCollectionFilterService()->setOutcomeFilter($outcomeFilter);

        return $this->getTaskCollectionFilterService()->getRemoteIdCount();
    }


    /**
     * @return int[]|null
     */
    private function getRemoteTaskIds() {
        if ($this->getRequestFilter() == 'all' && is_null($this->getRequestType())) {
            return null;
        }

        return $this->getFilteredTaskCollectionRemoteIds(
            $this->getRequestFilter(),
            $this->getRequestType()
        );
    }

    public function getInvalidOwnerResponse(Request $request)
    {
        foreach (['website', 'test_id'] as $requiredRequestAttribute) {
            if (!$request->attributes->has($requiredRequestAttribute)) {
                return new Response('', 400);
            }
        }

        if ($this->getUserService()->isLoggedIn()) {
            return $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Results:not-authorised.html.twig',
                array_merge($this->getDefaultViewParameters(), [
                    'test_id' => $request->attributes->get('test_id'),
                    'website' => $this->getUrlViewValues($request->attributes->get('website')),
                ])
            );
        }

        $redirectParameters = json_encode(array(
            'route' => 'view_test_progress_index_index',
            'parameters' => array(
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            )
        ));

        $this->container->get('session')->getFlashBag()->set('user_signin_error', 'test-not-logged-in');

        return new RedirectResponse($this->generateUrl('view_user_signin_index', array(
            'redirect' => base64_encode($redirectParameters)
        ), true));
    }
}
