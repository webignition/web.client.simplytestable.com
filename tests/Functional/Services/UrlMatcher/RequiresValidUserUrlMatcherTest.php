<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresValidUserUrlMatcherTest extends AbstractBaseTestCase
{
    use ValidUserUrlMatcherMatchNotExpectedDataProviderTrait;

    /**
     * @var UrlMatcher
     */
    private $requiresValidUserUrlMatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->requiresValidUserUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_valid_user_url_matcher'
        );
    }

    /**
     * @dataProvider requiresValidUserUrlMatcherMatchExpectedDataProvider
     */
    public function testRequiresValidUserUrlMatcherMatchExpected(string $path)
    {
        $this->assertTrue($this->requiresValidUserUrlMatcher->match($path));
    }

    public function requiresValidUserUrlMatcherMatchExpectedDataProvider(): array
    {
        return [
            '/account/* [1]' => [
                'path' => '/account/news-subscriptions/update/',
            ],
            '/account/* [2]' => [
                'path' => '/account/',
            ],
            '/' => [
                'path' => '/',
            ],
            '/recent-tests/' => [
                'path' => '/recent-tests/',
            ],
            '/history/* [1]' => [
                'path' => '/history/',
            ],
            '/history/* [2]' => [
                'path' => '/history/1',
            ],
            '*/finished-summary/ [2]' => [
                'path' => '/http://example.com//1234/finished-summary/',
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
            '*/re-test/' => [
                'path' => '/http://example.com//1234/re-test/',
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
            '/test/start/' => [
                'path' => '/test/start/',
            ],
        ];
    }

    /**
     * @dataProvider requiresValidUserUrlMatcherMatchNotExpectedDataProvider
     */
    public function testRequiresValidUserUrlMatcherMatchNotExpected(string $path)
    {
        $this->assertFalse($this->requiresValidUserUrlMatcher->match($path));
    }
}
