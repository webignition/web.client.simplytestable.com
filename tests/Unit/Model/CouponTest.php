<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Model\Coupon;

class CouponTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Coupon
     */
    private $coupon;

    private $emptyCouponValues = [
        'code' => null,
        'isActive' => null,
        'intro' =>  null,
        'percentOff' => null,
    ];

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
     */
    public function testJsonSerialize(
        ?string $code,
        ?bool $isActive,
        ?string $intro,
        ?string $percentOff,
        array $expectedSerializedCoupon
    ) {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedSerializedCoupon, $this->coupon->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
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
     */
    public function testToString(
        ?string $code,
        ?bool $isActive,
        ?string $intro,
        ?string $percentOff,
        string $expectedStringRepresentation
    ) {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedStringRepresentation, $this->coupon->__toString());
    }

    public function toStringDataProvider(): array
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
                        'percent_off' => '20'
                    ]),
                ]
            ),
        ];
    }

    /**
     * @dataProvider getPriceModifierDataProvider
     */
    public function testGetPriceModifier(
        ?string $code,
        ?bool $isActive,
        ?string $intro,
        ?string $percentOff,
        float $expectedPriceModifier
    ) {
        $this->populateCoupon($code, $isActive, $intro, $percentOff);

        $this->assertEquals($expectedPriceModifier, $this->coupon->getPriceModifier());
    }

    public function getPriceModifierDataProvider(): array
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

    private function populateCoupon(?string $code, ?bool $isActive, ?string $intro, ?string $percentOff)
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
