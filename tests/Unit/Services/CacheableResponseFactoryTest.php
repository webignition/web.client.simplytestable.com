<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Services\CacheableResponseFactory;
use App\Services\SystemUserService;
use App\Tests\Factory\MockFactory;
use SimplyTestable\PageCacheBundle\Services\CacheableResponseFactory as BaseCacheableResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use webignition\SimplyTestableUserManagerInterface\UserManagerInterface;
use webignition\SimplyTestableUserModel\User;

class CacheableResponseFactoryTest extends \PHPUnit\Framework\TestCase
{
    const PRIVATE_USER_USERNAME = 'user@example.com';

    /**
     * @dataProvider createResponseDataProvider
     */
    public function testCreateResponse(
        array $requestAttributes,
        array $cacheValidatorParameters,
        BaseCacheableResponseFactory $baseCacheableResponseFactory,
        UserManagerInterface $userManager
    ) {
        $request = new Request();

        $request->attributes->replace($requestAttributes);

        $cacheableResponseFactory = new CacheableResponseFactory($baseCacheableResponseFactory, $userManager);
        $cacheableResponseFactory->createResponse($request, $cacheValidatorParameters);
    }

    public function createResponseDataProvider(): array
    {
        return [
            'public user, not logged in' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'cacheValidatorParameters' => [],
                'baseCacheableResponseFactory' => MockFactory::createBaseCacheableResponseFactory([
                    'createResponse' => [
                        'withArgs' => function (Request $request, array $parameters) {
                            $this->assertEquals('foo', $request->attributes->get('_route'));
                            $this->assertEquals(
                                $parameters,
                                [
                                    'user' => 'public',
                                    'is_logged_in' => false,
                                ]
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'userManager' => MockFactory::createUserManager([
                    'getUser' => [
                        'return' => SystemUserService::getPublicUser(),
                    ],
                    'isLoggedIn' => [
                        'return' => false,
                    ],
                ]),
                'expectedResponseEtag' => 'W/"foo"',
                'expectedResponseLastModified' => new \DateTime(
                    '2018-01-01 10:00:00 GMT'
                ),
                'expectedResponseStatusCode' => 200,
                'expectedIsNotModified' =>  false,
            ],
            'private user, is logged in, no additional cache validator parameters' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'cacheValidatorParameters' => [],
                'baseCacheableResponseFactory' => MockFactory::createBaseCacheableResponseFactory([
                    'createResponse' => [
                        'withArgs' => function (Request $request, array $parameters) {
                            $this->assertEquals('foo', $request->attributes->get('_route'));
                            $this->assertEquals(
                                $parameters,
                                [
                                    'user' => self::PRIVATE_USER_USERNAME,
                                    'is_logged_in' => true,
                                ]
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'userManager' => MockFactory::createUserManager([
                    'getUser' => [
                        'return' => new User(self::PRIVATE_USER_USERNAME),
                    ],
                    'isLoggedIn' => [
                        'return' => true,
                    ],
                ]),
            ],
            'private user, is logged in, with additional cache validator parameters' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'cacheValidatorParameters' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                'baseCacheableResponseFactory' => MockFactory::createBaseCacheableResponseFactory([
                    'createResponse' => [
                        'withArgs' => function (Request $request, array $parameters) {
                            $this->assertEquals('foo', $request->attributes->get('_route'));
                            $this->assertEquals(
                                $parameters,
                                [
                                    'user' => self::PRIVATE_USER_USERNAME,
                                    'is_logged_in' => true,
                                    'key1' => 'value1',
                                    'key2' => 'value2',
                                ]
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'userManager' => MockFactory::createUserManager([
                    'getUser' => [
                        'return' => new User('user@example.com'),
                    ],
                    'isLoggedIn' => [
                        'return' => true,
                    ],
                ]),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
