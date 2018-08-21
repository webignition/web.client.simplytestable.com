<?php

namespace App\Tests\Unit\Services;

use App\Services\UserHydrator;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\InvalidCipherTextException;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserHydratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getUserDataProvider
     *
     * @param UserSerializer $userSerializer
     * @param SessionInterface $session
     * @param User|null $expectedUser
     * @param Request $request
     */
    public function testGetUser(
        UserSerializer $userSerializer,
        SessionInterface $session,
        $expectedUser,
        Request $request = null
    ) {
        $requestStack = new RequestStack();

        if (!empty($request)) {
            $requestStack->push($request);
        }

        $userHydrator = new UserHydrator($requestStack, $userSerializer, $session);

        $this->assertEquals($expectedUser, $userHydrator->getUser());
    }

    /**
     * @return array
     */
    public function getUserDataProvider()
    {
        $privateUser = new User('user@example.com');

        return [
            'no current request, no user in session' => [
                'userSerializer' => new UserSerializer(null),
                'session' => $this->createSession(''),
                'expectedUser' => null,
            ],
            'no user in request, no user in session' => [
                'userSerializer' => new UserSerializer(null),
                'session' => $this->createSession(''),
                'expectedUser' => null,
                'request' => new Request(),
            ],
            'invalid user in request, no user in session' => [
                'userSerializer' => $this->createUserSerializer('deserializeFromString', 'invalid', null),
                'session' => $this->createSession(''),
                'expectedUser' => null,
                'request' => new Request([], [], [], [
                    UserHydrator::USER_COOKIE_KEY => 'invalid',
                ]),
            ],
            'corrupt user in request, no user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserializeFromString',
                    'corrupt',
                    new InvalidCipherTextException()
                ),
                'session' => $this->createSession(''),
                'expectedUser' => null,
                'request' => new Request([], [], [], [
                    UserHydrator::USER_COOKIE_KEY => 'corrupt',
                ]),
            ],
            'no user in request, empty user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserialize',
                    '',
                    null
                ),
                'session' => $this->createSession(''),
                'expectedUser' => null,
                'request' => new Request(),
            ],
            'no user in request, invalid user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserialize',
                    'invalid session user',
                    null
                ),
                'session' => $this->createSession('invalid session user'),
                'expectedUser' => null,
                'request' => new Request(),
            ],
            'no user in request, corrupt user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserialize',
                    'corrupt session user',
                    new InvalidCipherTextException()
                ),
                'session' => $this->createSession('corrupt session user'),
                'expectedUser' => null,
                'request' => new Request(),
            ],
            'valid user in request, no user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserializeFromString',
                    'valid serialized user',
                    $privateUser
                ),
                'session' => $this->createSession(''),
                'expectedUser' => $privateUser,
                'request' => new Request([], [], [], [
                    UserHydrator::USER_COOKIE_KEY => 'valid serialized user',
                ]),
            ],
            'no user in request, valid user in session' => [
                'userSerializer' => $this->createUserSerializer(
                    'deserialize',
                    'valid serialized user',
                    $privateUser
                ),
                'session' => $this->createSession('valid serialized user'),
                'expectedUser' => $privateUser,
                'request' => new Request(),
            ],
        ];
    }

    /**
     * @param string|null $sessionUser
     *
     * @return MockInterface|SessionInterface
     */
    private function createSession($sessionUser)
    {
        $session = \Mockery::mock(SessionInterface::class);

        $session
            ->shouldReceive('has')
            ->withArgs([UserHydrator::SESSION_USER_KEY])
            ->andReturn(!is_null($sessionUser));

        $session
            ->shouldReceive('get')
            ->withArgs([UserHydrator::SESSION_USER_KEY])
            ->andReturn($sessionUser);

        return $session;
    }

    /**
     * @param string $method
     * @param string|array $arg
     * @param User|InvalidCipherTextException|null $response
     *
     * @return MockInterface|UserSerializer
     */
    private function createUserSerializer(
        string $method,
        $arg,
        $response
    ) {
        $userSerializer = \Mockery::mock(UserSerializer::class);

        if ($response instanceof InvalidCipherTextException) {
            $userSerializer
                ->shouldReceive($method)
                ->withArgs([$arg])
                ->andThrow($response);
        } else {
            $userSerializer
                ->shouldReceive($method)
                ->withArgs([$arg])
                ->andReturn($response);
        }

        return $userSerializer;
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
