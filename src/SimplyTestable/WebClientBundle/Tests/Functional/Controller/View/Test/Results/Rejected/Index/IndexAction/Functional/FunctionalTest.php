<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Rejected\Index\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\FunctionalTest as BaseFunctionalTest;

/**
 * rejection cases:
 * curl errors
 *
 */
abstract class FunctionalTest extends BaseFunctionalTest {

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

}