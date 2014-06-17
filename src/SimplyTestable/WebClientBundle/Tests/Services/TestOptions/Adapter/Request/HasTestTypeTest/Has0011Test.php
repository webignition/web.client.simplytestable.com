<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has0011Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return false;
    }

    protected function getRequestHasCssValidation() {
        return false;
    }

    protected function getRequestHasJsStaticAnalysis() {
        return true;
    }

    protected function getRequestHasLinkIntegrity() {
        return true;
    }
}
