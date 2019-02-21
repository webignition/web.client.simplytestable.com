<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcherInterface;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresValidUserUrlMatcherTest extends AbstractBaseTestCase
{
    const SERVICE_ID = 'simplytestable.web_client.requires_valid_user_url_matcher';

    use ValidUserUrlMatcherMatchNotExpectedDataProviderTrait;

    /**
     * @var UrlMatcherInterface
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
    public function testMatchNotExpected(string $path)
    {
        $this->assertFalse($this->urlMatcher->match($path));
    }
}
