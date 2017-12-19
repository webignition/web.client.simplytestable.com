<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Progress\Index\IndexAction\Action;

class FinishedTest extends RedirectTest {

    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/results/';
    }


}