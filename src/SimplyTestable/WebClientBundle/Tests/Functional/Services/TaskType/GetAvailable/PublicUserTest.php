<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskType\GetAvailable;

class PublicUserTest extends GetAvailableTest {

    protected function getExpectedTaskTypeKeys() {
        return [
            'html-validation',
            'css-validation',
        ];
    }

}
