<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has1101Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return true;
    }

    protected function getRequestHasCssValidation() {
        return true;
    }

    protected function getRequestHasJsStaticAnalysis() {
        return false;
    }

    protected function getRequestHasLinkIntegrity() {
        return true;
    }
}
