<?php

namespace App\Model;

use App\Entity\Test\Test;

class DecoratedTest
{
    private $test;

    public function __construct(Test $test)
    {
        $this->test = $test;
    }
}
