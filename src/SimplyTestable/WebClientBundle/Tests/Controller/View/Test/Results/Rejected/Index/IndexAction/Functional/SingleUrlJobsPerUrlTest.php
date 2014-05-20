<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Rejected\Index\IndexAction\Functional;

class SingleUrlJobsPerUrlTest extends FunctionalTest {

    public function testNavbarContainsSignInButton() {
        $this->assertNavbarContainsSignInButton();
    }

    public function testNavbarSignInButtonUrl() {
        $this->assertNavbarSignInButtonUrl();
    }

    public function testNavbarContainsCreateAccountButton() {
        $this->assertNavbarContainsCreateAccountButton();
    }

    public function testNavbarCreateAccountButtonUrl() {
        $this->assertNavbarCreateAccountButtonUrl();
    }

    public function testHasRejectionReason() {
        $reasons = $this->getScopedCrawler()->filter('#reason');
        $this->assertEquals(1, $reasons->count());
    }

    public function testRejectionReasonContent() {
        $reasons = $this->getScopedCrawler()->filter('#reason');

        foreach ($reasons as $reason) {
            $this->assertDomNodeContainsText($reason, 'reached the limit of free single-page');
        }
    }

}