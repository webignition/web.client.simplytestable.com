<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\CookiesFeature;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\FunctionalTest as BaseFunctionalTest;

class IsEnabledTest extends BaseFunctionalTest {

    protected function getRequestQueryData() {
        return array(
            'filter' => 'with-errors'
        );
    }

    public function testIsEnabled() {
        $this->assertEquals(
            'enabled',
            trim($this->getScopedCrawler()->filter('#cookies-action-badge .status')->text())
        );
    }

}