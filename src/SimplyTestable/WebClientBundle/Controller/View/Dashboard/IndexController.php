<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use Symfony\Component\HttpFoundation\ParameterBag;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser {

    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter
     */
    private $testOptionsAdapter = null;


    /**
     * @var \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    private $taskTypeService = null;


    protected function modifyViewName($viewName) {
        return str_replace(
            ':Dashboard',
            ':bs3/Dashboard',
            $viewName
        );
    }
    
    public function indexAction() {
        return $this->renderCacheableResponse([
            'available_task_types' => $this->getTaskTypeService()->getAvailable(),
            'task_types' => $this->getTaskTypeService()->get(),
            'test_options' => $this->getTestOptionsArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
            'test_start_error' => $this->getFlash('test_start_error'),
            'website' => $this->getUrlViewValues($this->getPersistentValue('website'))
        ]);
    }


    private function getTestOptionsArray() {
        $testOptionsParameterBag = new ParameterBag(
            $this->getDefaultedRequestValues($this->container->getParameter('test_options')['names_and_default_values'])
        );

        $this->getTestOptionsAdapter()->setRequestData($testOptionsParameterBag);

        if ($this->hasTestStartError()) {
            $this->getTestOptionsAdapter()->setInvertInvertableOptions(true);
        }

        $testOptions = $this->getTestOptionsAdapter()->getTestOptions()->__toKeyArray();

        if (!isset($testOptions['cookies']) || count($testOptions['cookies']) === 0) {
            $testOptions['cookies'] = [[
                'name' => null,
                'value' => null
            ]];
        }

        return $testOptions;
    }

    public function getCacheValidatorParameters() {
        return [
            'test_start_error' => $this->getFlash('test_start_error', false),
            'website' => $this->getRequest()->query->has('website') ? $this->getRequest()->query->get('website') : '',
            'available_task_types' => json_encode($this->getTaskTypeService()->getAvailable()),
            'task_types' => json_encode($this->container->getParameter('task_types')),
            'test_options' => json_encode($this->getTestOptionsArray()),
            'css_validation_ignore_common_cdns' => json_encode($this->getCssValidationCommonCdnsToIgnore()),
            'js_static_analysis_ignore_common_cdns' => json_encode($this->getJsStaticAnalysisCommonCdnsToIgnore()),
        ];
    }


    /**
     * @return bool
     */
    private function hasTestStartError() {
        return !is_null($this->getFlash('test_start_error', false));
    }


//    /**
//     *
//     * @return array
//     */
//    private function getAvailableTaskTypes() {
//        $this->getAvailableTaskTypeService()->setUser($this->getUser());
//        $this->getAvailableTaskTypeService()->setIsAuthenticated($this->isLoggedIn());
//
//        return $this->getAvailableTaskTypeService()->get();
//    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');

            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');

            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getTaskTypeService()->getAvailable());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);

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


    /**
     *
     * @param string $url
     * @return string[]
     */
    private function getUrlViewValues($url = null) {
        if (is_null($url) || trim($url) === '') {
            return array();
        }

        $websiteUrl = new \webignition\NormalisedUrl\NormalisedUrl($url);
        $websiteUrl->getConfiguration()->enableConvertIdnToUtf8();

        $utf8Raw = (string)$websiteUrl;
        $utf8Truncated_40 = $this->getTruncatedString($utf8Raw, 40);
        $utf8Truncated_50 = $this->getTruncatedString($utf8Raw, 50);
        $utf8Truncated_64 = $this->getTruncatedString($utf8Raw, 64);

        $utf8Schemeless = $this->trimUrl($utf8Raw);

        $utf8SchemelessTruncated_40 = $this->getTruncatedString($utf8Schemeless, 40);
        $utf8SchemelessTruncated_50 = $this->getTruncatedString($utf8Schemeless, 50);
        $utf8SchemelessTruncated_64 = $this->getTruncatedString($utf8Schemeless, 64);

        return array(
            'raw' => $url,
            'utf8' => array(
                'raw' => $utf8Raw,
                'truncated_40' => $utf8Truncated_40,
                'truncated_50' => $utf8Truncated_50,
                'truncated_64' => $utf8Truncated_64,
                'is_truncated_40' => ($utf8Raw != $utf8Truncated_40),
                'is_truncated_50' => ($utf8Raw != $utf8Truncated_50),
                'is_truncated_64' => ($utf8Raw != $utf8Truncated_64),
                'schemeless' => array(
                    'raw' => $utf8Schemeless,
                    'truncated_40' => $utf8SchemelessTruncated_40,
                    'truncated_50' => $utf8SchemelessTruncated_50,
                    'truncated_64' => $utf8SchemelessTruncated_64,
                    'is_truncated_40' => ($utf8Schemeless != $utf8SchemelessTruncated_40),
                    'is_truncated_50' => ($utf8Schemeless != $utf8SchemelessTruncated_50),
                    'is_truncated_64' => ($utf8Schemeless != $utf8SchemelessTruncated_64)
                )
            )
        );
    }


    private function trimUrl($url) {
        $url = $this->getSchemelessUrl($url);

        if (substr($url, strlen($url) - 1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    private function getTruncatedString($input, $maxLength = 64) {
        if (mb_strlen($input) <= $maxLength) {
            return $input;
        }

        return mb_substr($input, 0, $maxLength);
    }


    /**
     *
     * @param string $url
     * @return string
     */
    private function getSchemelessUrl($url) {
        $schemeMarkers = array(
            'http://',
            'https://'
        );

        foreach ($schemeMarkers as $schemeMarker) {
            if (substr($schemeMarker, 0, strlen($schemeMarker)) == $schemeMarker) {
                return substr($url, strlen($schemeMarker));
            }
        }

        return $url;
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    private function getTaskTypeService() {
        if (is_null($this->taskTypeService)) {
            $this->taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
            $this->taskTypeService->setUser($this->getUser());

            if (!$this->getUser()->equals($this->getUserService()->getPublicUser())) {
                $this->taskTypeService->setUserIsAuthenticated();
            }
        }

        return $this->taskTypeService;
    }

}