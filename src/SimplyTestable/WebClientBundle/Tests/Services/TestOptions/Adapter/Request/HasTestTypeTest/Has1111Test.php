<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\HasTestTypeTest;

class Has1111Test extends HasTestTypeTest {

    protected function getRequestHasHtmlValidation() {
        return true;
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
