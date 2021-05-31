<?php

namespace App\Services;

interface UrlMatcherInterface
{
    public function match(string $urlPath): bool;
}
