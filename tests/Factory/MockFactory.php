<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Factory;

use Mockery\MockInterface;
use App\Repository\TaskOutputRepository;
use App\Services\Factory\TaskOutputFactory;
use App\Services\UserService;
use SimplyTestable\PageCacheBundle\Services\CacheableResponseFactory as BaseCacheableResponseFactory;
use Twig_Environment;
use webignition\SimplyTestableUserManagerInterface\UserManagerInterface;

class MockFactory
{
    /**
     * @return MockInterface|Twig_Environment
     */
    public static function createTwig(array $calls = [])
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
     * @return MockInterface|UserService
     */
    public static function createUserService(array $calls = [])
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
     * @return MockInterface|UserManagerInterface
     */
    public static function createUserManager(array $calls = [])
    {
        $userService = \Mockery::mock(UserManagerInterface::class);

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
     * @return MockInterface|BaseCacheableResponseFactory
     */
    public static function createBaseCacheableResponseFactory(array $calls = [])
    {
        $baseCacheableResponseFactory = \Mockery::mock(BaseCacheableResponseFactory::class);

        if (isset($calls['createResponse'])) {
            $baseCacheableResponseFactory
                ->shouldReceive('createResponse')
                ->withArgs($calls['createResponse']['withArgs'])
                ->andReturn($calls['createResponse']['return']);
        }

        return $baseCacheableResponseFactory;
    }

    /**
     * @return MockInterface|TaskOutputRepository
     */
    public static function createTaskOutputRepository(array $calls = [])
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
     * @return MockInterface|TaskOutputFactory
     */
    public static function createTaskOutputFactory(array $calls = [])
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
