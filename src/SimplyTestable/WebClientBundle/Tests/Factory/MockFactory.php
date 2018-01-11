<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Mockery\MockInterface;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class MockFactory
{
    /**
     * @param array $calls
     *
     * @return MockInterface|PostmarkMessage
     */
    public static function createPostmarkMessage(array $calls = [])
    {
        /* @var PostmarkMessage|MockInterface $message */
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
            if ($calls['setTextMessage'] === true) {
                $message
                    ->shouldReceive('setTextMessage');
            } else {
                $message
                    ->shouldReceive('setTextMessage')
                    ->with($calls['setTextMessage']['with']);
            }
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

    /**
     * @param array $calls
     *
     * @return MockInterface|EngineInterface
     */
    public static function createTemplatingEngine($calls = [])
    {
        $templatingEngine = \Mockery::mock(EngineInterface::class);

        if (isset($calls['renderResponse'])) {
            $templatingEngine
                ->shouldReceive('renderResponse')
                ->withArgs($calls['renderResponse']['withArgs'])
                ->andReturn($calls['renderResponse']['return']);
        }

        return $templatingEngine;
    }
}
