<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Progress\Index\IndexAction\Action;

class SuppliedWebsiteDoesNotMatchTestWebsiteTest extends RedirectTest {

    const WEBSITE = 'http://www.example.com/';
    const TEST_ID = 1;


    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/';
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID
        );
    }


    protected function getRequestAttributes() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID
        );
    }
}