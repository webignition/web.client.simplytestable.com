<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Progress;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    const RESULTS_PREPARATION_THRESHOLD = 100;

    private $testStateLabelMap = array(
        'new' => 'New, waiting to start',
        'queued' => 'waiting for first test to begin',
        'resolving' => 'Resolving website',
        'resolved' => 'Resolving website',
        'preparing' => 'Finding URLs to test: looking for sitemap or news feed',
        'crawling' => 'Finding URLs to test',
        'failed-no-sitemap' => 'Finding URLs to test: preparing to crawl'
    );

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


    protected function getAllowedContentTypes() {
        return array_merge(['application/json'], parent::getAllowedContentTypes());
    }


    public function indexAction($website, $test_id) {
        if ($this->getTestService()->isFinished($this->getTest())) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
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

        if ($this->getTestService()->isFinished($this->getTest())) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id
            ), true));
        }

        $this->getTestOptionsAdapter()->setRequestData($this->getRemoteTest()->getOptions());
        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();
//
//        $remoteTaskIds = ($this->getRequestFilter() == 'all' && is_null($this->getRequestType()))
//            ? null
//            : $this->getFilteredTaskCollectionRemoteIds(
//                $this->getTest(),
//                $this->getRequestFilter(),
//                $this->getRequestType()
//            );
//
//        $tasks = $this->getTaskService()->getCollection($this->getTest(), $remoteTaskIds);
//
//        if ($this->getRawRequestFilter() != $this->getRequestFilter()) {
//            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
//                'website' => $website,
//                'test_id' => $test_id,
//                'filter' => $this->getDefaultRequestFilter()
//            ), true));
//        }
//

        $viewData = array(
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'this_url' => $this->getProgressUrl($website, $test_id),
//            'is_public' => $this->getTestService()->getRemoteTestService()->isPublic(),
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'remote_test' => $this->requestIsForApplicationJson($this->getRequest()) ? $this->getRemoteTest()->__toArray() : $this->getRemoteTest(),
            'state_label' => $this->getStateLabel(),
//            'is_owner' => $isOwner,
//            'type' => $this->getRequestType(),
//            'type_label' => $this->getTaskTypeLabel($this->getRequestType()),
//            'filter' => $this->getRequestFilter(),
//            'filter_label' => ucwords(str_replace('-', ' ', $this->getRequestFilter())),
//            'task_types' => $this->container->getParameter('task_types'),
            'test_options' => $testOptions->__toKeyArray(),
            'available_task_types' => $this->getAvailableTaskTypes(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
//            'tasks' => $this->getTasksGroupedByUrl($tasks),
//            'filtered_task_counts' => $this->getFilteredTaskCounts(),
//            'domain_test_count' => $this->getTestService()->getRemoteTestService()->getFinishedCount($this->getTest()->getWebsite()),
            'test_authentication_enabled' => $this->getRemoteTest()->hasParameter('http-auth-username'),
            'test_cookies_enabled' => $this->getRemoteTest()->hasParameter('cookies'),
            'test_cookies' => $this->getTestCookies(),
//            'default_css_validation_options' => array(
//                'ignore-warnings' => 1,
//                'vendor-extensions' => 'warn',
//                'ignore-common-cdns' => 1
//            ),
//            'default_js_static_analysis_options' => array(
//                'ignore-common-cdns' => 1,
//                'jslint-option-maxerr' => 50,
//                'jslint-option-indent' => 4,
//                'jslint-option-maxlen' => 256
//            ),
        );

        return $this->renderCacheableResponse($viewData);
    }


    public function getCacheValidatorParameters() {
        /**
         * timestamp for json response
         */

        return array(
            'rand' => rand()
        );

//        $test = $this->getTest();
//
//        return array(
//            'website' => $this->getRequest()->attributes->get('website'),
//            'test_id' => $this->getRequest()->attributes->get('test_id'),
//            'is_public' => $this->getTestService()->getRemoteTestService()->isPublic(),
//            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
//            'type' => $this->getRequestType(),
//            'filter' => $this->getRequestFilter(),
//        );
    }


    private function getStateLabel() {
        $label = (isset($this->testStateLabelMap[$this->getTest()->getState()])) ? $this->testStateLabelMap[$this->getTest()->getState()] : '';

        if ($this->getTest()->getState() == 'in-progress') {
            $label = $this->getRemoteTest()->getCompletionPercent().'% done';
        }

        if (in_array($this->getTest()->getState(), ['queued', 'in-progress'])) {
            $label = $this->getRemoteTest()->getUrlCount() . ' urls, ' . $this->getRemoteTest()->getTaskCount() . ' tests; ' . $label;
        }

        if ($this->getTest()->getState() == 'crawling') {
            $label .= ': '. $this->getRemoteTest()->getCrawl()->processed_url_count .' checked, ' . $this->getRemoteTest()->getCrawl()->discovered_url_count.' of '. $this->getRemoteTest()->getCrawl()->limit .' found';        }

        return $label;
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