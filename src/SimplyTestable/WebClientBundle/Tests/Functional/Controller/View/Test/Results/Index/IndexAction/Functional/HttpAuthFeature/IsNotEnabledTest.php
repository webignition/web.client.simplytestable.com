<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\HttpAuthFeature;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\FunctionalTest as BaseFunctionalTest;

class IsNotEnabledTest extends BaseFunctionalTest {

    protected function getRequestQueryData() {
        return array(
            'filter' => 'without-errors'
        );
    }

    public function testIsNotEnabled() {
        $this->assertEquals(
            'not enabled',
            $this->getScopedCrawler()->filter('#http-authentication-action-badge .status')->text()
        );
    }

}