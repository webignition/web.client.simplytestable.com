<?php

namespace Tests\AppBundle\Functional\Services;

use AppBundle\Services\CouponService;
use Symfony\Component\HttpFoundation\Request;
use Tests\AppBundle\Functional\AbstractBaseTestCase;

class CouponServiceTest extends AbstractBaseTestCase
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

        $this->couponService = self::$container->get(CouponService::class);
    }

    public function testGetHasNoCouponCookieInRequest()
    {
        $this->couponService->setRequest(new Request());

        $this->assertFalse($this->couponService->has());
        $this->assertNull($this->couponService->get());
    }

    public function testGetHasCouponCookieInRequest()
    {
        $this->couponService->setRequest(new Request([], [], [], [
            CouponService::COUPON_COOKIE_NAME => 'TMS',
        ]));

        $this->assertTrue($this->couponService->has());
        $this->assertEquals(
            [
                'code' => 'TMS',
                'active' => true,
                'intro' =>
                    '<p><strong>Hey there TMS listener!</strong> You get 20% off the lifetime of your account.</p>',
                'percent_off' => 20,
            ],
            $this->couponService->get()->jsonSerialize()
        );
    }
}
