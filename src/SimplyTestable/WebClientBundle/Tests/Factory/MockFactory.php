<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Mockery\Mock;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class MockFactory
{
    /**
     * @param array $calls
     *
     * @return Mock|PostmarkMessage
     */
    public static function createPostmarkMessage(array $calls = [])
    {
        /* @var PostmarkMessage|Mock $message */
        $message = \Mockery::mock(PostmarkMessage::class);

        if (isset($calls['setFrom'])) {
            $message
                ->shouldReceive('setFrom');
        }

        if (isset($calls['setSubject'])) {
            $message
                ->shouldReceive('setSubject')
                ->with($calls['setSubject']['with']);
        }

        if (isset($calls['setTextMessage'])) {
            $message
                ->shouldReceive('setTextMessage');
        }

        if (isset($calls['addTo'])) {
            $message
                ->shouldReceive('addTo')
                ->with($calls['addTo']['with']);
        }

        if (isset($calls['send'])) {
            $message
                ->shouldReceive('send')
                ->andReturn($calls['send']['return']);
        }

        return $message;
    }
}
