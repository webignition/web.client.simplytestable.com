<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\ByTaskType\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    const DEFAULT_TASK_TYPE = 'html+validation';

    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath()));
    }

    protected function getRouteParameters() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => 1,
            'task_type' => self::DEFAULT_TASK_TYPE
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

    public function testLongUrlInPageTitleIsTruncated() {
        $expectedTitleUrl = substr(str_replace('http://', '', self::WEBSITE), 0, 64) . '…';
        $this->assertTitleContainsText($expectedTitleUrl);
    }

}