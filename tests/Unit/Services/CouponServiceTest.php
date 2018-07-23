<?php

namespace App\Tests\Unit\Services;

use App\Services\CouponService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->couponService = new CouponService();
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param array $couponData
     * @param Request $request
     * @param array $expectedSerializedCoupon
     */
    public function testGet(array $couponData, Request $request, $expectedSerializedCoupon)
    {
        $this->couponService->setCouponData($couponData);
        $this->couponService->setRequest($request);

        $coupon = $this->couponService->get();

        if (is_null($expectedSerializedCoupon)) {
            $this->assertNull($coupon);
        } else {
            $this->assertEquals($expectedSerializedCoupon, $coupon->jsonSerialize());
        }
    }

    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            'no coupon data, no coupon in request' => [
                'couponData' => [],
                'request' => new Request(),
                'expectedSerializedCoupon' => null,
            ],
            'non-matching coupon in request' => [
                'couponData' => [],
                'request' => new Request(
                    [],
                    [],
                    [],
                    [
                        CouponService::COUPON_COOKIE_NAME => 'bar',
                    ]
                ),
                'expectedSerializedCoupon' => null,
            ],
            'matching coupon in request; coupon data all set' => [
                'couponData' => [
                    'foo' => [
                        'active' => true,
                        'percent_off' => 20,
                        'intro' => 'Intro',
                    ],
                ],
                'request' => new Request(
                    [],
                    [],
                    [],
                    [
                        CouponService::COUPON_COOKIE_NAME => 'foo',
                    ]
                ),
                'expectedSerializedCoupon' => [
                    'code' => 'foo',
                    'active' => true,
                    'intro' => 'Intro',
                    'percent_off' => 20,
                ],
            ],
            'matching coupon in request; coupon data not set, use defaults' => [
                'couponData' => [
                    'foo' => [],
                ],
                'request' => new Request(
                    [],
                    [],
                    [],
                    [
                        CouponService::COUPON_COOKIE_NAME => 'foo',
                    ]
                ),
                'expectedSerializedCoupon' => [
                    'code' => 'foo',
                    'active' => false,
                    'intro' => '',
                    'percent_off' => 0,
                ],
            ],
        ];
    }
}
