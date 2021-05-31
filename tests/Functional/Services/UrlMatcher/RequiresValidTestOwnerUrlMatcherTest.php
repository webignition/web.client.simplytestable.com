<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcherInterface;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresValidTestOwnerUrlMatcherTest extends AbstractBaseTestCase
{
    const SERVICE_ID = 'simplytestable.web_client.requires_valid_test_owner_url_matcher';

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
     * @dataProvider matchNotExpectedDataProvider
     */
    public function testMatchNotExpected(string $path)
    {
        $this->assertFalse($this->urlMatcher->match($path));
    }

    public function matchNotExpectedDataProvider(): array
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
