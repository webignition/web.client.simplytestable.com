<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\HttpAuthFeature;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\FunctionalTest as BaseFunctionalTest;

class IsEnabledTest extends BaseFunctionalTest {

    protected function getRequestQueryData() {
        return array(
            'filter' => 'without-errors'
        );
    }

    public function testIsEnabled() {
        $this->assertEquals(
            'enabled',
            $this->getScopedCrawler()->filter('#http-authentication-action-badge .status')->text()
        );
    }

}