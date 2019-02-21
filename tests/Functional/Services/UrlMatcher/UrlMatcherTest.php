<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class UrlMatcherTest extends AbstractBaseTestCase
{
    use ValidUserUrlMatcherMatchNotExpectedDataProviderTrait;

    /**
     * @var UrlMatcher
     */
    private $requiresPrivateUserUrlMatcher;

    /**
     * @var UrlMatcher
     */
    private $requiresValidTestOwnerUrlMatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->requiresPrivateUserUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_private_user_url_matcher'
        );

        $this->requiresValidTestOwnerUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_valid_test_owner_url_matcher'
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

    /**
     * @dataProvider requiresValidTestOwnerUrlMatcherMatchExpectedDataProvider
     */
    public function testRequiresValidTestOwnerUrlMatcherMatchExpected(string $path)
    {
        $this->assertTrue($this->requiresValidTestOwnerUrlMatcher->match($path));
    }

    public function requiresValidTestOwnerUrlMatcherMatchExpectedDataProvider(): array
    {
        return [
            '*/finished-summary/' => [
                'path' => '/http://example.com//1234/finished-summary/',
            ],
            '*/preparing/' => [
                'path' => '/http://example.com//1234/results/preparing/',
            ],
            'task */results/ [1]' => [
                'path' => '/website/http://example.com//test_id/1234/task_id/567/results/',
            ],
            'task */results// [2]' => [
                'path' => '/http://example.com//1234/task_id/567/results//',
            ],
            'test */results// [1]' => [
                'path' => '/http://example.com//1234/results//',
            ],
            'test */results// [2]' => [
                'path' => '/http://example.com//1234/http://example.com/foo.html/html+validation/results/',
            ],
            'test */results/* [1]' => [
                'path' => '/http://example.com//1234/results/rejected/',
            ],
            'test */results/* [2]' => [
                'path' => '/http://example.com//1234/results/html+validation/foo',
            ],
            '*/url-limit-notification/' => [
                'path' => '/http://example.com//1234/url-limit-notification/',
            ],
            '*/progress/' => [
                'path' => '/http://example.com//1234/progress/',
            ],
            '*/tasklist/' => [
                'path' => '/http://example.com//1234/tasklist/',
            ],
            '*/tasks/ids/' => [
                'path' => '/http://example.com//1234/tasks/ids/',
            ],
            '*/tasks/ids/unretrieved/*' => [
                'path' => '/http://example.com//1234/tasks/ids/unretrieved/*',
            ],
        ];
    }

    /**
     * @dataProvider requiresValidTestOwnerUrlMatcherMatchNotExpectedDataProvider
     */
    public function testRequiresValidTestOwnerUrlMatcherMatchNotExpected(string $path)
    {
        $this->assertFalse($this->requiresValidTestOwnerUrlMatcher->match($path));
    }

    public function requiresValidTestOwnerUrlMatcherMatchNotExpectedDataProvider(): array
    {
        return [
            '/' => [
                'path' => '/',
            ],
            '/signout/' => [
                'path' => '/signout/',
            ],
            '/test/../cancel/' => [
                'path' => '/test/http://example.com//1234/cancel/',
            ],
            '/test/../cancel-crawl/' => [
                'path' => '/test/http://example.com//1234/cancel-crawl/',
            ],
        ];
    }
}
