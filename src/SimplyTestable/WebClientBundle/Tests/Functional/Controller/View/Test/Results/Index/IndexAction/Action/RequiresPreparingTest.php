<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Action;

class RequiresPreparingTest extends RedirectTest {

    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/results/preparing/';
    }


}