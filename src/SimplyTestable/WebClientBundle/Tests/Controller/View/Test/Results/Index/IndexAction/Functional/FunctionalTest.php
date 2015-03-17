<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    const RETEST_FEATURE_SELECTOR = '#retest-button';
    const LOCK_UNLOCK_FEATURE_SELECTOR = '#lock-unlock-button';
    const COOKIES_AVAILABLE_OPTION_SELECTOR = '[data-for=cookies-options-modal]';
    const COOKIES_NOT_AVAILABLE_OPTION_SELECTOR = '[data-for=cookies-account-required-modal]';
    const HTTP_AUTH_AVAILABLE_OPTION_SELECTOR = '[data-for=http-authentication-options-modal]';
    const HTTP_AUTH_NOT_AVAILABLE_OPTION_SELECTOR = '[data-for=http-authentication-account-required-modal]';
    const HISTORY_LINK_SELECTOR = '.history';
    const TEST_OPTIONS_SELECTOR = '#test-options-control';

    /**
     * user summary
     */

    /**
     * task type summary
     *   with html only
     *   with css only
     *   with js only
     *   with link integrity only
     *   with combinations
     * filter tabs
     *   only showing those that are relevant
     * lock/unlock
     *   - never present for public user tests: done
     *   - present on private test if owner: done
     *   - not present on unlocked test if not user: done
     * http auth feature:
     *   - is marked as enabled when set on test: done
     *   - is not marked as enabled when not set on test: done
     * custom cookies feature:
     *   - is marked as enabled when set on test: done
     *   - is not marked as enabled when not set on test: done
     * summary stats
     */


    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    const TEST_ID = 1;

    public function setUp() {
        parent::setUp();

        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath()));

        // for setting private user
        //$this->setUser($this->makeUser());
    }

    protected function getRouteParameters() {
        return array_merge(array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ), $this->getRequestQueryData());
    }

    protected function assertRetestFeatureIsPresent() {
        $this->assertElementBySelector(self::RETEST_FEATURE_SELECTOR, true);
    }

    protected function assertRetestFeatureIsNotPresent() {
        $this->assertElementBySelector(self::RETEST_FEATURE_SELECTOR, false);
    }

    protected function assertLockUnlockFeatureIsPresent() {
        $this->assertElementBySelector(self::LOCK_UNLOCK_FEATURE_SELECTOR, true);
    }

    protected function assertLockUnlockFeatureIsNotPresent() {
        $this->assertElementBySelector(self::LOCK_UNLOCK_FEATURE_SELECTOR, false);
    }

    protected function assertCookiesOptionAvailableChangeControlIsPresent() {
        $this->assertElementBySelector(self::COOKIES_AVAILABLE_OPTION_SELECTOR, true);
    }

    protected function assertCookiesOptionAvailableChangeControlIsNotPresent() {
        $this->assertElementBySelector(self::COOKIES_AVAILABLE_OPTION_SELECTOR, false);
    }

    protected function assertCookiesOptionNotAvailableChangeControlIsPresent() {
        $this->assertElementBySelector(self::COOKIES_NOT_AVAILABLE_OPTION_SELECTOR, true);
    }

    protected function assertCookiesOptionNotAvailableChangeControlIsNotPresent() {
        $this->assertElementBySelector(self::COOKIES_NOT_AVAILABLE_OPTION_SELECTOR, false);
    }

    protected function assertHttpAuthOptionAvailableChangeControlIsPresent() {
        $this->assertElementBySelector(self::HTTP_AUTH_AVAILABLE_OPTION_SELECTOR, true);
    }

    protected function assertHttpAuthOptionAvailableChangeControlIsNotPresent() {
        $this->assertElementBySelector(self::HTTP_AUTH_AVAILABLE_OPTION_SELECTOR, false);
    }

    protected function assertHttpAuthOptionNotAvailableChangeControlIsPresent() {
        $this->assertElementBySelector(self::HTTP_AUTH_NOT_AVAILABLE_OPTION_SELECTOR, true);
    }

    protected function assertHttpAuthOptionNotAvailableChangeControlIsNotPresent() {
        $this->assertElementBySelector(self::HTTP_AUTH_NOT_AVAILABLE_OPTION_SELECTOR, false);
    }

    protected function assertHistoryLinkIsPresent() {
        $this->assertElementBySelector(self::HISTORY_LINK_SELECTOR, true);
    }

    protected function assertHistoryLinkIsNotPresent() {
        $this->assertElementBySelector(self::HISTORY_LINK_SELECTOR, false);
    }

    protected function assertTestOptionsChangeControlIsPresent() {
        foreach ($this->getScopedCrawler()->filter(self::TEST_OPTIONS_SELECTOR) as $node) {
            $this->assertDomNodeContainsText($node, 'Choose what to test (with advanced options)');
        }
    }

    protected function assertTestOptionsChangeControlIsNotPresent() {
        foreach ($this->getScopedCrawler()->filter(self::TEST_OPTIONS_SELECTOR) as $node) {
            $this->assertDomNodeContainsText($node, 'See what was tested');
        }
    }

    private function assertElementBySelector($selector, $isPresent) {
        $this->assertEquals(
            ($isPresent) ? 1 : 0,
            $this->getScopedCrawler()->filter($selector)->count()
        );
    }



}