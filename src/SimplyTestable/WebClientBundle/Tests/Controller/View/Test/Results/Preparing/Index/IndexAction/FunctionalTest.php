<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Preparing\Index\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\FunctionalTest as BaseFunctionalTest;

class FunctionalTest extends BaseFunctionalTest {

    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';

    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath()));
    }

    protected function getRouteParameters() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => 1
        );
    }

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

    public function testContentWebsiteIsPresent() {
        $contentWebsites = $this->getScopedCrawler()->filter('#website');
        $this->assertEquals(1, $contentWebsites->count());
    }

    public function testContentWebsiteTitle() {
        $this->assertEquals(self::WEBSITE, $this->getScopedCrawler()->filter('#website')->extract('title')[0]);
    }

    public function testContentWebsiteContent() {
        $contentWebsites = $this->getScopedCrawler()->filter('#website');
        $expectedWebsiteContent = substr(self::WEBSITE, 0, 40) . '…';

        foreach ($contentWebsites as $contentWebsite) {
            $this->assertDomNodeContainsText($contentWebsite, $expectedWebsiteContent);
        }
    }

}