<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\RegisterableDomainService;
use App\Tests\Functional\AbstractBaseTestCase;

class RegisterableDomainServiceTest extends AbstractBaseTestCase
{
    /**
     * @var RegisterableDomainService
     */
    private $registerableDomainService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->registerableDomainService = self::$container->get(RegisterableDomainService::class);
    }

    /**
     * @dataProvider getRegisterableDomainInvalidUrlDataProvider
     */
    public function testGetRegisterableDomainInvalidUrl(string $url)
    {
        $this->assertNull($this->registerableDomainService->getRegisterableDomain($url));
    }

    public function getRegisterableDomainInvalidUrlDataProvider(): array
    {
        return [
            'not a url; trailing slash' => [
                'url' => '/',
            ],
            'not a url; css' => [
                'url' => 'vertical-align:top',
            ],
            'not a url; windows path' => [
                'url' => 'e:\Cursus\NHA\Webdesign\Les 5\huiswerk\huiswerk_les5.htm',
            ],
            'unroutable: loopback IP' => [
                'url' => 'http://127.0.0.1',
            ],
            'unroutable: foo' => [
                'url' => 'http://foo',
            ],
        ];
    }

    /**
     * @dataProvider getRegisterableDomainValidUrlDataProvider
     */
    public function testGetRegisterableDomainValidUrl(string $url, string $expectedRegisterableDomain)
    {
        $this->assertEquals($expectedRegisterableDomain, $this->registerableDomainService->getRegisterableDomain($url));
    }

    public function getRegisterableDomainValidUrlDataProvider(): array
    {
        return [
            '.com; top level' => [
                'url' => 'http://example.com',
                'expectedRegisterableDomain' => 'example.com',
            ],
            '.com; subdomain' => [
                'url' => 'http://foo.example.com',
                'expectedRegisterableDomain' => 'example.com',
            ],
            '.co.uk; top level' => [
                'url' => 'http://example.co.uk',
                'expectedRegisterableDomain' => 'example.co.uk',
            ],
            '.co.uk; subdomain' => [
                'url' => 'http://foo.example.co.uk',
                'expectedRegisterableDomain' => 'example.co.uk',
            ],
        ];
    }
}
