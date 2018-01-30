<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services;

use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;
use Symfony\Component\HttpFoundation\Request;

class CacheValidatorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createResponseDataProvider
     *
     * @param array $requestAttributes
     * @param array $requestHeaders
     * @param array $cacheValidatorParameters
     * @param CacheValidatorHeadersService $cacheValidatorHeadersService
     * @param UserService $userService
     * @param string $expectedResponseEtag
     * @param \DateTime $expectedResponseLastModified
     * @param int $expectedResponseStatusCode
     * @param bool $expectedIsNotModified
     */
    public function testCreateResponse(
        array $requestAttributes,
        array $requestHeaders,
        array $cacheValidatorParameters,
        CacheValidatorHeadersService $cacheValidatorHeadersService,
        UserService $userService,
        $expectedResponseEtag,
        $expectedResponseLastModified,
        $expectedResponseStatusCode,
        $expectedIsNotModified
    ) {
        $request = new Request();

        $request->attributes->replace($requestAttributes);
        $request->headers->replace($requestHeaders);

        $cacheValidatorService = new CacheValidatorService($cacheValidatorHeadersService, $userService);

        $response = $cacheValidatorService->createResponse($request, $cacheValidatorParameters);

        $this->assertEquals('must-revalidate, public', $response->headers->get('cache-control'));
        $this->assertEquals($expectedResponseEtag, $response->getEtag());
        $this->assertEquals($expectedResponseLastModified, $response->getLastModified());
        $this->assertEquals($expectedResponseStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedIsNotModified, $cacheValidatorService->isNotModified($response));
    }

    /**
     * @return array
     */
    public function createResponseDataProvider()
    {
        return [
            'private user, is logged in, no additional cache validator parameters' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'requestHeaders' => [],
                'cacheValidatorParameters' => [],
                'cacheValidatorHeadersService' => MockFactory::createCacheValidatorHeadersService([
                    'get' => [
                        'withArgs' => function (CacheValidatorIdentifier $cacheValidatorIdentifier) {
                            $this->assertEquals(
                                [
                                    'route' => 'foo',
                                    'user' => 'user@example.com',
                                    'is_logged_in' => 'true',
                                ],
                                $cacheValidatorIdentifier->getParameters()
                            );

                            return true;
                        },
                        'return' => ModelFactory::createCacheValidatorHeaders([
                            ModelFactory::CACHE_VALIDATOR_HEADERS_IDENTIFIER => 'foo',
                            ModelFactory::CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE => new \DateTime(
                                '2018-01-01 10:00:00 GMT'
                            ),
                        ]),
                    ],
                ]),
                'userService' => MockFactory::createUserService([
                    'getUser' => [
                        'return' => new User('user@example.com'),
                    ],
                    'isLoggedIn' => [
                        'return' => true,
                    ],
                ]),
                'expectedResponseEtag' => 'W/"' . md5('foo') . '"',
                'expectedResponseLastModified' => new \DateTime(
                    '2018-01-01 10:00:00 GMT'
                ),
                'expectedResponseStatusCode' => 200,
                'expectedIsNotModified' =>  false,
            ],
            'private user, is logged in, with additional cache validator parameters' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'requestHeaders' => [],
                'cacheValidatorParameters' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                'cacheValidatorHeadersService' => MockFactory::createCacheValidatorHeadersService([
                    'get' => [
                        'withArgs' => function (CacheValidatorIdentifier $cacheValidatorIdentifier) {
                            $this->assertEquals(
                                [
                                    'route' => 'foo',
                                    'user' => 'user@example.com',
                                    'is_logged_in' => 'true',
                                    'key1' => 'value1',
                                    'key2' => 'value2',
                                ],
                                $cacheValidatorIdentifier->getParameters()
                            );

                            return true;
                        },
                        'return' => ModelFactory::createCacheValidatorHeaders([
                            ModelFactory::CACHE_VALIDATOR_HEADERS_IDENTIFIER => 'foo',
                            ModelFactory::CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE => new \DateTime(
                                '2018-01-01 10:00:00 GMT'
                            ),
                        ]),
                    ],
                ]),
                'userService' => MockFactory::createUserService([
                    'getUser' => [
                        'return' => new User('user@example.com'),
                    ],
                    'isLoggedIn' => [
                        'return' => true,
                    ],
                ]),
                'expectedResponseEtag' => 'W/"' . md5('foo') . '"',
                'expectedResponseLastModified' => new \DateTime(
                    '2018-01-01 10:00:00 GMT'
                ),
                'expectedResponseStatusCode' => 200,
                'expectedIsNotModified' =>  false,
            ],
            'public user, not logged in, request accept header' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'requestHeaders' => [
                    'accept' => 'application/json',
                ],
                'cacheValidatorParameters' => [],
                'cacheValidatorHeadersService' => MockFactory::createCacheValidatorHeadersService([
                    'get' => [
                        'withArgs' => function (CacheValidatorIdentifier $cacheValidatorIdentifier) {
                            $this->assertEquals(
                                [
                                    'route' => 'foo',
                                    'user' => 'public',
                                    'is_logged_in' => 'false',
                                    'http-header-accept' => 'application/json'
                                ],
                                $cacheValidatorIdentifier->getParameters()
                            );

                            return true;
                        },
                        'return' => ModelFactory::createCacheValidatorHeaders([
                            ModelFactory::CACHE_VALIDATOR_HEADERS_IDENTIFIER => 'foo',
                            ModelFactory::CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE => new \DateTime(
                                '2018-01-01 10:00:00 GMT'
                            ),
                        ]),
                    ],
                ]),
                'userService' => MockFactory::createUserService([
                    'getUser' => [
                        'return' => new User(UserService::PUBLIC_USER_USERNAME),
                    ],
                    'isLoggedIn' => [
                        'return' => false,
                    ],
                ]),
                'expectedResponseEtag' => 'W/"' . md5('foo') . '"',
                'expectedResponseLastModified' => new \DateTime(
                    '2018-01-01 10:00:00 GMT'
                ),
                'expectedResponseStatusCode' => 200,
                'expectedIsNotModified' =>  false,
            ],
            'private user, is logged in, foo' => [
                'requestAttributes' => [
                    '_route' => 'foo',
                ],
                'requestHeaders' => [
                    'if-modified-since' => '2018-01-01 11:00:00 GMT'
                ],
                'cacheValidatorParameters' => [],
                'cacheValidatorHeadersService' => MockFactory::createCacheValidatorHeadersService([
                    'get' => [
                        'withArgs' => function (CacheValidatorIdentifier $cacheValidatorIdentifier) {
                            $this->assertEquals(
                                [
                                    'route' => 'foo',
                                    'user' => 'user@example.com',
                                    'is_logged_in' => 'true',
                                ],
                                $cacheValidatorIdentifier->getParameters()
                            );

                            return true;
                        },
                        'return' => ModelFactory::createCacheValidatorHeaders([
                            ModelFactory::CACHE_VALIDATOR_HEADERS_IDENTIFIER => 'foo',
                            ModelFactory::CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE => new \DateTime(
                                '2018-01-01 10:00:00 GMT'
                            ),
                        ]),
                    ],
                ]),
                'userService' => MockFactory::createUserService([
                    'getUser' => [
                        'return' => new User('user@example.com'),
                    ],
                    'isLoggedIn' => [
                        'return' => true,
                    ],
                ]),
                'expectedResponseEtag' => 'W/"' . md5('foo') . '"',
                'expectedResponseLastModified' => null,
                'expectedResponseStatusCode' => 304,
                'expectedIsNotModified' =>  true,
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
