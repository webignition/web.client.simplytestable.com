<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresPrivateUserUrlMatcherTest extends AbstractBaseTestCase
{
    const SERVICE_ID = 'simplytestable.web_client.requires_private_user_url_matcher';

    use ValidUserUrlMatcherMatchNotExpectedDataProviderTrait;

    /**
     * @var UrlMatcher
     */
    private $urlMatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->urlMatcher = self::$container->get(self::SERVICE_ID);
    }

    /**
     * @dataProvider matchExpectedDataProvider
     */
    public function testMatchExpected(string $path)
    {
        $this->assertTrue($this->urlMatcher->match($path));
    }

    public function matchExpectedDataProvider(): array
    {
        return [
            '/account/* [1]' => [
                'path' => '/account/news-subscriptions/update/',
            ],
            '/account/* [2]' => [
                'path' => '/account/',
            ],
        ];
    }

    /**
     * @dataProvider requiresValidUserUrlMatcherMatchNotExpectedDataProvider
     */
    public function testMatchNotExpected(string $path)
    {
        $this->assertFalse($this->urlMatcher->match($path));
    }
}
