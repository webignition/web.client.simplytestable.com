<?php

namespace Tests\WebClientBundle\Helper;

class MockeryArgumentValidator
{
    /**
     * @param string[] $strings
     *
     * @return \Closure
     */
    public static function stringContains($strings)
    {
        $stringMatchCount = 0;

        return function ($arg) use ($strings, $stringMatchCount) {
            foreach ($strings as $string) {
                if (strpos($arg, $string) !== false) {
                    $stringMatchCount++;
                }
            }

            return $stringMatchCount === count($strings);
        };
    }
}
