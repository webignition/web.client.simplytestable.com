<?php

namespace App\Tests\Services;

use GuzzleHttp\Handler\MockHandler;

class HttpMockHandler extends MockHandler
{
    public function appendFixtures(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->append($fixture);
        }
    }
}
