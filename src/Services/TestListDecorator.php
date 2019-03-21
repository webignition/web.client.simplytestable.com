<?php

namespace App\Services;

use App\Model\DecoratedTest;
use App\Model\TestList;

class TestListDecorator
{
    public function decorate(TestList $testList): TestList
    {
        $decoratedTests = [];

        foreach ($testList as $test) {
            $decoratedTests[] = new DecoratedTest($test);
        }

        return $testList->withTests($decoratedTests);
    }
}
