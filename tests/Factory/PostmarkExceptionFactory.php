<?php

namespace App\Tests\Factory;

use Postmark\Models\PostmarkException;

class PostmarkExceptionFactory
{
    public static function create($postmarkApiErrorCode)
    {
        $postmarkException = new PostmarkException();
        $postmarkException->postmarkApiErrorCode = $postmarkApiErrorCode;

        return $postmarkException;
    }
}
