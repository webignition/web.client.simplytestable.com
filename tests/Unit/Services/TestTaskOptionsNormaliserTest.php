<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Services\TestTaskOptionsNormaliser;

class TestTaskOptionsNormaliserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestTaskOptionsNormaliser
     */
    private $normaliser;

    protected function setUp()
    {
        parent::setUp();

        $this->normaliser = new TestTaskOptionsNormaliser();
    }

    /**
     * @dataProvider normaliseDataProvider
     */
    public function testNormalise(array $taskTypes, array $taskTypeOptionsCollection, array $expectedTaskOptions)
    {
        $taskOptions = $this->normaliser->normalise($taskTypes, $taskTypeOptionsCollection);

        $this->assertEquals($expectedTaskOptions, $taskOptions);
    }

    public function normaliseDataProvider(): array
    {
        return [
            'no task types, no task type options collection' => [
                'taskTypes' => [],
                'taskTypeOptionsCollection' => [],
                'expectedTaskOptions' => [],
            ],
            'no task types, has task type options collection' => [
                'taskTypes' => [],
                'taskTypeOptionsCollection' => [
                    'CSS validation' => [
                        'ignore-warnings' => '1',
                        'vendor-extensions' => 'warn',
                    ],
                ],
                'expectedTaskOptions' => [
                    'css-validation-ignore-warnings' => '1',
                    'css-validation-vendor-extensions' => 'warn',
                ],
            ],
            'has task types, no task type options collection' => [
                'taskTypes' => [
                    'HTML Validation',
                    'CSS Validation',
                    'Link Integrity',
                ],
                'taskTypeOptionsCollection' => [],
                'expectedTaskOptions' => [
                    'html-validation' => '1',
                    'css-validation' => '1',
                    'link-integrity' => '1',
                ],
            ],
        ];
    }
}
