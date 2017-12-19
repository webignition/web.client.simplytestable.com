<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Action;

abstract class CausesRedirectTest extends ActionTest {

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

    public function testResponseLocationIsForMainTestResults() {
        $this->assertResponseLocationHeader('/http://example.com//1/');
    }


}