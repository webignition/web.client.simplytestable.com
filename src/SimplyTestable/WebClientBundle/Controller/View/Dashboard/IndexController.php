<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser {

    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private $testOptionsAdapter = null;

    protected function modifyViewName($viewName) {
        return str_replace(
            ':Dashboard',
            ':bs3/Dashboard',
            $viewName
        );
    }
    
    public function indexAction() {
        $testStartError = '';

        $this->getTestOptionsAdapter()->setRequestData($this->defaultAndPersistentTestOptionsToParameterBag());
        if ($testStartError != '') {
            $this->getTestOptionsAdapter()->setInvertInvertableOptions(true);
        }

        $testOptions = $this->getTestOptionsAdapter()->getTestOptions();

        $viewData = [
            'available_task_types' => $this->getAvailableTaskTypes(),
            'task_types' => $this->container->getParameter('task_types'),
            'test_options' => $testOptions->__toKeyArray(),
            'css_validation_ignore_common_cdns' => $this->getCssValidationCommonCdnsToIgnore(),
            'js_static_analysis_ignore_common_cdns' => $this->getJsStaticAnalysisCommonCdnsToIgnore(),
        ];

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {        
        return array(
            'rand' => rand()
        );
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
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request
     */
    private function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');

            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');

            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getAvailableTaskTypes());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);

            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }
        }

        return $this->testOptionsAdapter;
    }


    /**
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function defaultAndPersistentTestOptionsToParameterBag() {
        $testOptionsParameters = $this->container->getParameter('test_options');
        return new \Symfony\Component\HttpFoundation\ParameterBag($this->getPersistentValues($testOptionsParameters['names_and_default_values']));
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

}