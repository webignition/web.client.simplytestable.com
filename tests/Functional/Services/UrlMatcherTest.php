<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\UrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class UrlMatcherTest extends AbstractBaseTestCase
{
    /**
     * @var UrlMatcher
     */
    private $requiresValidUserUrlMatcher;

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

        $this->requiresValidUserUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_valid_user_url_matcher'
        );

        $this->requiresPrivateUserUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_private_user_url_matcher'
        );

        $this->requiresValidTestOwnerUrlMatcher = self::$container->get(
            'simplytestable.web_client.requires_valid_test_owner_url_matcher'
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

    public function requiresValidUserUrlMatcherMatchNotExpectedDataProvider(): array
    {
        return [
            '/signout/' => [
                'path' => '/signout/',
            ],
            '/signin/' => [
                'path' => '/signin/',
            ],
            '/signin/submit/' => [
                'path' => '/signin/submit/',
            ],
            '/reset-password/' => [
                'path' => '/reset-password/',
            ],
            '/reset-password/submit/' => [
                'path' => '/reset-password/submit/',
            ],
            '/reset-password/choose/submit/' => [
                'path' => '/reset-password/choose/submit/',
            ],
            '/reset-password/{email}/{token}/' => [
                'path' => '/reset-password/user@example.com/bsWc0lX5EGWhnFHU0QHMM5vP_gDZSwkCsW3CG3nEV10/',
            ],
            '/signup/' => [
                'path' => '/signup/',
            ],
            '/signup/submit/' => [
                'path' => '/signup/submit/',
            ],
            '/signup/confirm/{email}/' => [
                'path' => '/signup/confirm/user@example.com/',
            ],
            '/signup/invite/{token}/' => [
                'path' => '/signup/invite/bsWc0lX5EGWhnFHU0QHMM5vP_gDZSwkCsW3CG3nEV10/',
            ],
            '/signup/confirm/{email}/submit/' => [
                'path' => '/signup/confirm/user@example.com/submit/',
            ],
            '/signup/invite/{token}/accept/' => [
                'path' => '/signup/invite/bsWc0lX5EGWhnFHU0QHMM5vP_gDZSwkCsW3CG3nEV10/accept/',
            ],
            '/signup/confirm/{email}/resend/' => [
                'path' => '/signup/confirm/user@example.com/resend/',
            ],
            '*/lock/' => [
                'path' => '/http://example.com//1234/lock/',
            ],
            '*/unlock/' => [
                'path' => '/http://example.com//1234/unlock/',
            ],
            '/test/../cancel/' => [
                'path' => '/test/http://example.com//1234/cancel/',
            ],
            '/test/../cancel-crawl/' => [
                'path' => '/test/http://example.com//1234/cancel-crawl/',
            ],
        ];
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
