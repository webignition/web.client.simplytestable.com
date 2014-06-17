<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has0111Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return false;
    }

    protected function getRequestHasCssValidation() {
        return true;
    }

    protected function getRequestHasJsStaticAnalysis() {
        return true;
    }

    protected function getRequestHasLinkIntegrity() {
        return true;
    }
}
