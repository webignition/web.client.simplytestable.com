<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\GetTestTypeOptions\Features;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\ServiceTest;

class CookiesFeatureTest extends ServiceTest {

    public function testHasNoCookiesByDefault() {
        $this->assertEquals([], $this->getCookiesFeatureOptions());
    }

    public function testBlankCookiesAreRemoved() {
        $this->getRequestData()->set('cookies', [[
            'name' => null,
            'value' => null
        ]]);

        $this->assertEquals(['cookies' => []], $this->getCookiesFeatureOptions());

        //exit();
    }
    
    
    /**
     * 
     * @return array
     */
    private function getCookiesFeatureOptions() {
        return $this->getTestOptions()->getFeatureOptions('cookies');
    }    
}
