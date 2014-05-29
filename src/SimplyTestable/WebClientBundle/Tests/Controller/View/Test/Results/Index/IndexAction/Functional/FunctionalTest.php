<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    const LOCK_UNLOCK_FEATURE_SELECTOR = '#lock-unlock-button';

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
     *   - is marked as enabled when set on test
     *   - is not marked as enabled when not set on test
     * summary stats
     */


    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    const TEST_ID = 1;

    public function setUp() {
        parent::setUp();
        $this->removeAllTests();

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

    protected function assertLockUnlockFeatureIsPresent() {
        $this->assertEquals(
            1,
            $this->getScopedCrawler()->filter(self::LOCK_UNLOCK_FEATURE_SELECTOR)->count()
        );
    }


    protected function assertLockUnlockFeatureIsNotPresent() {
        $this->assertEquals(
            0,
            $this->getScopedCrawler()->filter(self::LOCK_UNLOCK_FEATURE_SELECTOR)->count()
        );
    }



}