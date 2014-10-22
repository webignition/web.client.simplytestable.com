<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\ByTaskType\IndexAction\Action;

class NoFilterTest extends RedirectTest {

    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/results/html+validation/by-error/';
    }


    protected function getActionMethodArguments() {
        return array(
            'website' => 'http://www.example.com/',
            'test_id' => 1,
            'task_type' => 'html validation'
        );
    }

    protected function getRequestAttributes() {
        return array(
            'website' => 'http://www.example.com/',
            'test_id' => 1,
            'task_type' => 'html validation'
        );
    }

}