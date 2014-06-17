<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has0100Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return false;
    }

    protected function getRequestHasCssValidation() {
        return true;
    }

    protected function getRequestHasJsStaticAnalysis() {
        return false;
    }

    protected function getRequestHasLinkIntegrity() {
        return false;
    }
}
