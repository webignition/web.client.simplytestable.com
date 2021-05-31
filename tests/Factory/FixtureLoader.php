<?php

namespace App\Tests\Factory;

class FixtureLoader
{
    public static function load(string $path): ?string
    {
        return file_get_contents(realpath(__DIR__ . '/../Fixtures' . $path));
    }
}
