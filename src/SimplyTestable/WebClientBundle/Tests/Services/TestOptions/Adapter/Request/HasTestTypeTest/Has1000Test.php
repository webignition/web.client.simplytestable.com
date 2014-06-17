<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has1000Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return true;
    }

    protected function getRequestHasCssValidation() {
        return false;
    }

    protected function getRequestHasJsStaticAnalysis() {
        return false;
    }

    protected function getRequestHasLinkIntegrity() {
        return false;
    }
}
