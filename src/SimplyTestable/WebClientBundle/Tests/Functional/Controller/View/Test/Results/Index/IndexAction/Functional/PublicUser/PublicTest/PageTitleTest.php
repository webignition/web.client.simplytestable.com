<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class PageTitleTest extends BaseFunctionalTest {

    public function testLongUrlInPageTitleIsTruncated() {
        $expectedTitleUrl = substr(str_replace('http://', '', self::WEBSITE), 0, 64) . '…';
        $this->assertTitleContainsText($expectedTitleUrl);
    }

}