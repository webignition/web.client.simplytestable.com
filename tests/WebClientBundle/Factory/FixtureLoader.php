<?php

namespace Tests\WebClientBundle\Factory;

class FixtureLoader
{
    /**
     * @param string $path
     *
     * @return bool|string
     */
    public static function load($path)
    {
        return file_get_contents(realpath(__DIR__ . '/../Fixtures' . $path));
    }
}
