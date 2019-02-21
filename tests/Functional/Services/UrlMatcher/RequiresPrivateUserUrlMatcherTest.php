<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresPrivateUserUrlMatcherTest extends AbstractBaseTestCase
{
    use ValidUserUrlMatcherMatchNotExpectedDataProviderTrait;

    /**
     * @var UrlMatcher
     */
    private $requiresPrivateUserUrlMatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->requiresPrivateUserUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_private_user_url_matcher'
        );
    }

    /**
     * @dataProvider requiresPrivateUserUrlMatcherMatchExpectedDataProvider
     */
    public function testRequiresPrivateUserUrlMatcherMatchExpected(string $path)
    {
        $this->assertTrue($this->requiresPrivateUserUrlMatcher->match($path));
    }

    public function requiresPrivateUserUrlMatcherMatchExpectedDataProvider(): array
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
    public function testRequiresPrivateUserUrlMatcherMatchNotExpected(string $path)
    {
        $this->assertFalse($this->requiresPrivateUserUrlMatcher->match($path));
    }
}
