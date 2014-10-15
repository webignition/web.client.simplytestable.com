<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\ByTaskType\IndexAction\Action;

class FailedNoSitemapTest extends RedirectTest {

    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/results/failed/no-urls-detected/';
    }


    protected function getActionMethodArguments() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1,
            'task_type' => 'foo'
        );
    }

    protected function getRequestAttributes() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1,
            'task_type' => 'foo'
        );
    }

}