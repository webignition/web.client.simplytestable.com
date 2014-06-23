<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\ServiceTest;

abstract class HasTestTypeTest extends ServiceTest {

    /**
     * @var \SimplyTestable\WebClientBundle\Model\TestOptions
     */
    private $testOptions;

    public function setUp() {
        parent::setUp();

        $this->getRequestData()->set('html-validation', $this->getRequestHasHtmlValidation() ? '1' : '0');
        $this->getRequestData()->set('css-validation', $this->getRequestHasCssValidation() ? '1' : '0');
        $this->getRequestData()->set('js-static-analysis', $this->getRequestHasJsStaticAnalysis() ? '1' : '0');
        $this->getRequestData()->set('link-integrity', $this->getRequestHasLinkIntegrity() ? '1' : '0');

        $testOptionsParameters = $this->container->getParameter('test_options');

        $this->getTaskTypeService()->setUserIsAuthenticated();

        $this->getRequestAdapter()->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
        $this->getRequestAdapter()->setAvailableTaskTypes($this->getTaskTypeService()->getAvailable());
        $this->getRequestAdapter()->setRequestData($this->getRequestData());

        $this->testOptions = $this->getRequestAdapter()->getTestOptions();
    }

    public function testHasSelectedTestTypes() {
        if ($this->getRequestHasHtmlValidation()) {
            $this->assertTrue($this->testOptions->hasTestType('HTML validation'));
        }

        if ($this->getRequestHasCssValidation()) {
            $this->assertTrue($this->testOptions->hasTestType('CSS validation'));
        }

        if ($this->getRequestHasJsStaticAnalysis()) {
            $this->assertTrue($this->testOptions->hasTestType('JS static analysis'));
        }

        if ($this->getRequestHasLinkIntegrity()) {
            $this->assertTrue($this->testOptions->hasTestType('Link integrity'));
        }
    }

    public function testHasNotUnselectedTestTypes() {
        if (!$this->getRequestHasHtmlValidation()) {
            $this->assertFalse($this->testOptions->hasTestType('HTML validation'));
        }

        if (!$this->getRequestHasCssValidation()) {
            $this->assertFalse($this->testOptions->hasTestType('CSS validation'));
        }

        if (!$this->getRequestHasJsStaticAnalysis()) {
            $this->assertFalse($this->testOptions->hasTestType('JS static analysis'));
        }

        if (!$this->getRequestHasLinkIntegrity()) {
            $this->assertFalse($this->testOptions->hasTestType('Link integrity'));
        }
    }


    /**
     * @return string
     */
    private function getRequestTestTypesBitmapFromClassName() {
        $classNameParts = explode('\\', get_class($this));
        return str_replace(['Has', 'Test'], '', $classNameParts[count($classNameParts) - 1]);
    }


    private function getRequestHasHtmlValidation() {
        return substr($this->getRequestTestTypesBitmapFromClassName(), 0, 1) == 1;
    }

    private function getRequestHasCssValidation() {
        return substr($this->getRequestTestTypesBitmapFromClassName(), 1, 1) == 1;
    }

    private function getRequestHasJsStaticAnalysis() {
        return substr($this->getRequestTestTypesBitmapFromClassName(), 2, 1) == 1;
    }

    private function getRequestHasLinkIntegrity() {
        return substr($this->getRequestTestTypesBitmapFromClassName(), 3, 1) == 1;
    }
}
