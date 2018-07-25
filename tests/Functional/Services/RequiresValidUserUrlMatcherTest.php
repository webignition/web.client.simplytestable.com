<?php

namespace App\Tests\Functional\Services;

use App\Services\RequiresValidUserUrlMatcher;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresValidUserUrlMatcherTest extends AbstractBaseTestCase
{
    /**
     * @var RequiresValidUserUrlMatcher
     */
    private $requiresValidUserUrlMatcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requiresValidUserUrlMatcher = self::$container->get(RequiresValidUserUrlMatcher::class);
    }

    /**
     * @dataProvider matchExpectedDataProvider
     *
     * @param string $path
     */
    public function testMatchExpected($path)
    {
        $this->assertTrue($this->requiresValidUserUrlMatcher->match($path));
    }

    /**
     * @return array
     */
    public function matchExpectedDataProvider()
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
            '*/lock/' => [
                'path' => '/http://example.com//1234/lock/',
            ],
            '*/unlock/' => [
                'path' => '/http://example.com//1234/unlock/',
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
            '/test/* [1]' => [
                'path' => '/test/start/',
            ],
            '/test/* [2]' => [
                'path' => '/test/http://example.com//1234/cancel/',
            ],
            '/test/* [3]' => [
                'path' => '/test/http://example.com//1234/cancel-crawl/',
            ],
        ];
    }

    /**
     * @dataProvider matchNotExpectedDataProvider
     *
     * @param string $path
     */
    public function testMatchNotExpected($path)
    {
        $this->assertFalse($this->requiresValidUserUrlMatcher->match($path));
    }

    /**
     * @return array
     */
    public function matchNotExpectedDataProvider()
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
        ];
    }
}
