<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Action;

class ResultsPresentedTest extends ActionTest {

    protected function getExpectedResponseStatusCode() {
        return 200;
    }

    protected function getRequestQueryData() {
        return array(
            'filter' => 'with-errors'
        );
    }

}