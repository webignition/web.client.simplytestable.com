<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\UrlMatcher;

use App\Services\UrlMatcherInterface;
use App\Tests\Functional\AbstractBaseTestCase;

class RequiresCompletedTestUrlMatcherTest extends AbstractBaseTestCase
{
    const SERVICE_ID = 'simplytestable.web_client.requires_completed_test_url_matcher';

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
            'view test results verbose' => [
                'path' => '/website/http://example.com//test_id/1234/results/',
            ],
            'view test results' => [
                'path' => '/http://example.com//1234/results/',
            ],
            'view test results failed no urls detected' => [
                'path' => '/http://example.com//1234/results/failed/no-urls-detected/',
            ],
            'view test results rejected' => [
                'path' => '/http://example.com//1234/results/rejected/',
            ],
            'view test results preparing' => [
                'path' => '/http://example.com//1234/results/preparing/',
            ],
            'view test results by task type, has by-error filter' => [
                'path' => '/http://example.com//1234/results/html+validation/by-error/',
            ],
            'view test results by task type, has by-page filter' => [
                'path' => '/http://example.com//1234/results/html+validation/by-page/',
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
            'action test task results by url' => [
                'path' => '/http://example.com/1/http://example.com/foo//html%20validation/results/',
            ],
            'view test results preparing stats' => [
                'path' => '/http://example.com//1234/results/preparing/stats/',
            ],
            'task results retrieve' => [
                'path' => '/http://example.com//1234/results/retrieve/',
            ],
            'view test results by task type, no filter' => [
                'path' => '/http://example.com//1234/results/html+validation/',
            ],
            'view task results verbose' => [
                'path' => '/website//http://example.com//test_id/1234/task_id/5678/results/',
            ],
            'view task results' => [
                'path' => '//http://example.com//1234/5678/results/',
            ],
        ];
    }
}
