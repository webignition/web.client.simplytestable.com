<?php

namespace App\Tests\Unit\Services;

use Mockery\MockInterface;
use App\Services\FlashBagValues;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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

        /* @var Session|MockInterface $session */
        $session = \Mockery::mock(Session::class);
        $this->flashBag = new FlashBag();

        $session
            ->shouldReceive('getFlashBag')
            ->andReturn($this->flashBag);

        $this->flashBagValues = new FlashBagValues($session);
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param array $existingFlashBagValues
     * @param array $keys
     * @param array $expectedReturnValues
     */
    public function testGet(array $existingFlashBagValues, array $keys, array $expectedReturnValues)
    {
        foreach ($existingFlashBagValues as $key => $value) {
            $this->flashBag->set($key, $value);
        }

        $returnValues = $this->flashBagValues->get($keys);

        $this->assertEquals($expectedReturnValues, $returnValues);
    }

    /**
     * @return array
     */
    public function getDataProvider()
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
     *
     * @param array $existingFlashBagValues
     * @param string $key
     * @param string|null $expectedReturnValue
     */
    public function testGetSingle(array $existingFlashBagValues, $key, $expectedReturnValue)
    {
        foreach ($existingFlashBagValues as $key => $value) {
            $this->flashBag->set($key, $value);
        }

        $returnValue = $this->flashBagValues->getSingle($key);

        $this->assertEquals($expectedReturnValue, $returnValue);
    }

    /**
     * @return array
     */
    public function getSingleDataProvider()
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
