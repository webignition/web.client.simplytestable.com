<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Services\RegisterableDomainService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

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

        $this->registerableDomainService = $this->container->get(RegisterableDomainService::class);
    }

    /**
     * @dataProvider getRegisterableDomainInvalidUrlDataProvider
     *
     * @param string $url
     */
    public function testGetRegisterableDomainInvalidUrl($url)
    {
        $this->assertNull($this->registerableDomainService->getRegisterableDomain($url));
    }

    /**
     * @return array
     */
    public function getRegisterableDomainInvalidUrlDataProvider()
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
     *
     * @param string $url
     * @param string $expectedRegisterableDomain
     */
    public function testGetRegisterableDomainValidUrl($url, $expectedRegisterableDomain)
    {
        $this->assertEquals($expectedRegisterableDomain, $this->registerableDomainService->getRegisterableDomain($url));
    }

    /**
     * @return array
     */
    public function getRegisterableDomainValidUrlDataProvider()
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
