<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskType;

class GetTest extends ServiceTest {

    public function testGet() {
        $this->assertEquals([
            'html-validation',
            'css-validation',
            'js-static-analysis',
            'link-integrity',
            'test-foo-bar'
        ], array_keys($this->getTaskTypeService()->get()));
    }


}
