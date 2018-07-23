<?php

namespace App\Tests\Factory;

use Mockery\MockInterface;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use App\Repository\TaskOutputRepository;
use App\Services\CacheValidatorHeadersService;
use App\Services\Factory\TaskOutputFactory;
use App\Services\UserManager;
use App\Services\UserService;
use Twig_Environment;

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
     * @return MockInterface|Twig_Environment
     */
    public static function createTwig($calls = [])
    {
        $twig = \Mockery::mock(Twig_Environment::class);

        if (isset($calls['render'])) {
            $twig
                ->shouldReceive('render')
                ->withArgs($calls['render']['withArgs'])
                ->andReturn($calls['render']['return']);
        }

        return $twig;
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