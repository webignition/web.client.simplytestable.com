<?php

namespace Tests\AppBundle\Unit\Model;

use App\Model\Coupon;

class CouponTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * @var array
     */
    private $emptyCouponValues = [
        'code' => null,
        'isActive' => null,
        'intro' =>  null,
        'percentOff' => null,
    ];

    /**
     * @var array
     */
    private $nonEmptyCouponValues = [
        'code' => 'foo',
        'isActive' => true,
        'intro' =>  'Intro',
        'percentOff' => 20,
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->coupon = new Coupon();
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param string $code
     * @param bool $isActive
     * @param string $intro
     * @param string $percentOff
     * @param array $expectedSerializedCoupon
     */
    public function testJsonSerialize($code, $isActive, $intro, $percentOff, $expectedSerializedCoupon)
    {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedSerializedCoupon, $this->coupon->jsonSerialize());
    }

    /**
     * @return array
     */
    public function jsonSerializeDataProvider()
    {
        return [
            'empty' => array_merge(
                $this->emptyCouponValues,
                [
                    'expectedSerializedCoupon' => [
                        'code' => '',
                        'active' => false,
                        'intro' => '',
                        'percent_off' => null
                    ],
                ]
            ),
            'non-empty' => array_merge(
                $this->nonEmptyCouponValues,
                [
                    'expectedSerializedCoupon' => [
                        'code' => 'foo',
                        'active' => true,
                        'intro' => 'Intro',
                        'percent_off' => 20
                    ],
                ]
            ),
        ];
    }

    /**
     * @dataProvider toStringDataProvider
     *
     * @param string $code
     * @param bool $isActive
     * @param string $intro
     * @param string $percentOff
     * @param array $expectedStringRepresentation
     */
    public function testToString($code, $isActive, $intro, $percentOff, $expectedStringRepresentation)
    {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedStringRepresentation, $this->coupon->__toString());
    }

    /**
     * @return array
     */
    public function toStringDataProvider()
    {
        return [
            'empty' => array_merge(
                $this->emptyCouponValues,
                [
                    'expectedSerializedCoupon' => json_encode([
                        'code' => '',
                        'active' => false,
                        'intro' => '',
                        'percent_off' => 0
                    ]),
                ]
            ),
            'non-empty' => array_merge(
                $this->nonEmptyCouponValues,
                [
                    'expectedSerializedCoupon' => json_encode([
                        'code' => 'foo',
                        'active' => true,
                        'intro' => 'Intro',
                        'percent_off' => 20
                    ]),
                ]
            ),
        ];
    }

    /**
     * @dataProvider getPriceModifierDataProvider
     *
     * @param string $code
     * @param bool $isActive
     * @param string $intro
     * @param string $percentOff
     * @param array $expectedPriceModifier
     */
    public function testGetPriceModifier($code, $isActive, $intro, $percentOff, $expectedPriceModifier)
    {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedPriceModifier, $this->coupon->getPriceModifier());
    }

    /**
     * @return array
     */
    public function getPriceModifierDataProvider()
    {
        return [
            'empty' => array_merge(
                $this->emptyCouponValues,
                [
                    'expectedPriceModifier' => 1,
                ]
            ),
            'non-empty' => array_merge(
                $this->nonEmptyCouponValues,
                [
                    'expectedPriceModifier' => 0.8,
                ]
            ),
        ];
    }

    /**
     * @param string $code
     * @param bool $isActive
     * @param string $intro
     * @param string $percentOff
     */
    private function populateCoupon($code, $isActive, $intro, $percentOff)
    {
        if (!is_null($code)) {
            $this->coupon->setCode($code);
        }

        if (!is_null($isActive)) {
            $this->coupon->setIsActive($isActive);
        }

        if (!is_null($intro)) {
            $this->coupon->setIntro($intro);
        }

        if (!is_null($percentOff)) {
            $this->coupon->setPercentOff($percentOff);
        }
    }
}
