<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Services\FlashBagValues;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashBagValuesServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FlashBagValues
     */
    private $flashBagValues;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->flashBag = new FlashBag();
        $this->flashBagValues = new FlashBagValues($this->flashBag);
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet(array $existingFlashBagValues, array $keys, array $expectedReturnValues)
    {
        foreach ($existingFlashBagValues as $key => $value) {
            $this->flashBag->set($key, $value);
        }

        $returnValues = $this->flashBagValues->get($keys);

        $this->assertEquals($expectedReturnValues, $returnValues);
    }

    public function getDataProvider(): array
    {
        return [
            'no existing flash bag values, no keys' => [
                'exitingFlashBagValues' => [],
                'keys' => [],
                'expectedReturnValues' => [],
            ],
            'has existing flash bag values, no keys' => [
                'exitingFlashBagValues' => [
                    'foo' => 'bar',
                ],
                'keys' => [],
                'expectedReturnValues' => [],
            ],
            'no existing flash bag values, has keys' => [
                'exitingFlashBagValues' => [],
                'keys' => [
                    'foo',
                    'foobar',
                ],
                'expectedReturnValues' => [],
            ],
            'has existing flash bag values, has keys (some not present in flash bag)' => [
                'exitingFlashBagValues' => [
                    'foo' => 'bar',
                ],
                'keys' => [
                    'foo',
                    'foobar',
                ],
                'expectedReturnValues' => [
                    'foo' => 'bar',
                ],
            ],
            'has existing flash bag values, has keys (all present in flash bag)' => [
                'exitingFlashBagValues' => [
                    'foo' => 'bar',
                    'foobar' => 'foo',
                ],
                'keys' => [
                    'foo',
                    'foobar',
                ],
                'expectedReturnValues' => [
                    'foo' => 'bar',
                    'foobar' => 'foo',
                ],
            ],
            'has array value and scalar value' => [
                'exitingFlashBagValues' => [
                    'foo' => [
                        'key' => 'value',
                    ],
                    'bar' => 'foobar',
                ],
                'keys' => [
                    'foo',
                    'bar',
                ],
                'expectedReturnValues' => [
                    'foo' => [
                        'key' => 'value',
                    ],
                    'bar' => 'foobar',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getSingleDataProvider
     */
    public function testGetSingle(array $existingFlashBagValues, string $key, ?string $expectedReturnValue)
    {
        foreach ($existingFlashBagValues as $key => $value) {
            $this->flashBag->set($key, $value);
        }

        $returnValue = $this->flashBagValues->getSingle($key);

        $this->assertEquals($expectedReturnValue, $returnValue);
    }

    public function getSingleDataProvider(): array
    {
        return [
            'no existing flash bag values, has key' => [
                'exitingFlashBagValues' => [],
                'key' => 'foo',
                'expectedReturnValue' => null,
            ],
            'has existing flash bag values, has key' => [
                'exitingFlashBagValues' => [
                    'foo' => 'bar',
                ],
                'key' => 'foo',
                'expectedReturnValue' => 'bar',
            ],
        ];
    }
}
