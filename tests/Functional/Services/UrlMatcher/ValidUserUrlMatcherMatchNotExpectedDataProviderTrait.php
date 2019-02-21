<?php

namespace App\Tests\Functional\Services\UrlMatcher;

trait ValidUserUrlMatcherMatchNotExpectedDataProviderTrait
{
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
}
