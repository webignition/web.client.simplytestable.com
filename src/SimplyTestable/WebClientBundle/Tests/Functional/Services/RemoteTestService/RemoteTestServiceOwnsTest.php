<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceOwnsTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->test = new Test();
        $this->test->setTestId(1);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($this->test);
    }

    public function testOwnsDirectOwner()
    {
        $this->test->setUser($this->user->getUsername());

        $this->assertTrue($this->remoteTestService->owns($this->user));
    }

    /**
     * @dataProvider ownsRemoteExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     */
    public function testOwnsRemoteException(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->remoteTestService->owns($this->user);
    }

    /**
     * @return array
     */
    public function ownsRemoteExceptionDataProvider()
    {
        return [
            'http 500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
        ];
    }

    public function testOwnsRemoteHttp403()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->assertFalse($this->remoteTestService->owns($this->user));
    }

    public function testOwnsOwnersDoesNotContain()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'owners' => [
                    'foo@example.com',
                    'bar@example.com',
                ],
            ]),
        ]);

        $this->assertFalse($this->remoteTestService->owns($this->user));
    }

    public function testOwnsOwnersContains()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
                'owners' => [
                    $this->user->getUsername(),
                    'bar@example.com',
                ],
            ]),
        ]);

        $this->assertTrue($this->remoteTestService->owns($this->user));
    }
}
