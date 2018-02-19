<?php

namespace Tests\WebClientBundle\Factory;

use Mockery\MockInterface;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use SimplyTestable\WebClientBundle\Services\Factory\TaskOutputFactory;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
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

        if (isset($calls['render'])) {
            $templatingEngine
                ->shouldReceive('render')
                ->withArgs($calls['render']['withArgs'])
                ->andReturn($calls['render']['return']);
        }

        return $templatingEngine;
    }

    /**
     * @param array $calls
     *
     * @return MockInterface|CacheValidatorHeadersService
     */
    public static function createCacheValidatorHeadersService($calls = [])
    {
        $cacheValidatorHeadersService = \Mockery::mock(CacheValidatorHeadersService::class);

        if (isset($calls['get'])) {
            $cacheValidatorHeadersService
                ->shouldReceive('get')
                ->withArgs($calls['get']['withArgs'])
                ->andReturn($calls['get']['return']);
        }

        return $cacheValidatorHeadersService;
    }

    /**
     * @param array $calls
     *
     * @return MockInterface|UserService
     */
    public static function createUserService($calls = [])
    {
        $userService = \Mockery::mock(UserService::class);

        if (isset($calls['getUser'])) {
            $userService
                ->shouldReceive('getUser')
                ->andReturn($calls['getUser']['return']);
        }

        if (isset($calls['isLoggedIn'])) {
            $userService
                ->shouldReceive('isLoggedIn')
                ->andReturn($calls['isLoggedIn']['return']);
        }

        return $userService;
    }

    /**
     * @param array $calls
     *
     * @return MockInterface|UserManager
     */
    public static function createUserManager($calls = [])
    {
        $userService = \Mockery::mock(UserManager::class);

        if (isset($calls['getUser'])) {
            $userService
                ->shouldReceive('getUser')
                ->andReturn($calls['getUser']['return']);
        }

        if (isset($calls['isLoggedIn'])) {
            $userService
                ->shouldReceive('isLoggedIn')
                ->andReturn($calls['isLoggedIn']['return']);
        }

        return $userService;
    }

    /**
     * @param array $calls
     *
     * @return MockInterface|TaskOutputRepository
     */
    public static function createTaskOutputRepository($calls = [])
    {
        $taskOutputRepository = \Mockery::mock(TaskOutputRepository::class);

        if (isset($calls['findOneBy'])) {
            $taskOutputRepository
                ->shouldReceive('findOneBy')
                ->with($calls['findOneBy']['with'])
                ->andReturn($calls['findOneBy']['return']);
        }

        return $taskOutputRepository;
    }

    /**
     * @param array $calls
     *
     * @return MockInterface|TaskOutputFactory
     */
    public static function createTaskOutputFactory($calls = [])
    {
        $taskOutputFactory = \Mockery::mock(TaskOutputFactory::class);

        if (isset($calls['create'])) {
            $taskOutputFactory
                ->shouldReceive('create')
                ->withArgs($calls['create']['withArgs'])
                ->andReturn($calls['create']['return']);
        }

        return $taskOutputFactory;
    }
}
