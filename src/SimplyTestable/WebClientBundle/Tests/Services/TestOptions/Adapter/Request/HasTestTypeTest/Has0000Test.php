<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has0000Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return false;
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
