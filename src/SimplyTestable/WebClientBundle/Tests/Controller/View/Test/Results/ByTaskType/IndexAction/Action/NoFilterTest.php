<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\ByTaskType\IndexAction\Action;

class NoFilterTest extends ActionTest {

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