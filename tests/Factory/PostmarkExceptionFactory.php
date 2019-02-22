<?php

namespace App\Tests\Factory;

use Postmark\Models\PostmarkException;

class PostmarkExceptionFactory
{
    public static function create(int $postmarkApiErrorCode): PostmarkException
    {
        $postmarkException = new PostmarkException();
        $postmarkException->postmarkApiErrorCode = $postmarkApiErrorCode;

        return $postmarkException;
    }
}
