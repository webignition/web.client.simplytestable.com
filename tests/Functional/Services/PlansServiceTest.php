<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\PlansService;
use App\Tests\Functional\AbstractBaseTestCase;

class PlansServiceTest extends AbstractBaseTestCase
{
    /**
     * @var PlansService
     */
    private $plansService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->plansService = self::$container->get(PlansService::class);
    }

    /**
     * @dataProvider getListDataProvider
     */
    public function testGetList(bool $listPremiumOnly, ?float $priceModifier, array $expectedListedPlans)
    {
        if ($listPremiumOnly) {
            $this->plansService->listPremiumOnly();
        }

        if ($priceModifier) {
            $this->plansService->setPriceModifier($priceModifier);
        }

        $list = $this->plansService->getList();

        $this->assertCount(count($expectedListedPlans), $list);

        foreach ($expectedListedPlans as $planName => $expectedListedPlan) {
            $listedPlan = $list[$planName];

            $this->assertEquals($planName, $listedPlan->getAccountPlan()->getName());
            $this->assertEquals($expectedListedPlan['price'], $listedPlan->getPrice());
            $this->assertEquals($expectedListedPlan['originalPrice'], $listedPlan->getOriginalPrice());
        }
    }

    public function getListDataProvider(): array
    {
        return [
            'listPremiumOnly: false, priceModifier): null' => [
                'listPremiumOnly' => false,
                'priceModifier' => null,
                'expectedListedPlans' => [
                    'Basic' => [
                        'price' => 0,
                        'originalPrice' => 0,
                    ],
                    'Personal' => [
                        'price' => 900,
                        'originalPrice' => 900,
                    ],
                    'Agency' => [
                        'price' => 1900,
                        'originalPrice' => 1900,
                    ],
                    'Business' => [
                        'price' => 5900,
                        'originalPrice' => 5900,
                    ],
                ],
            ],
            'listPremiumOnly: true, priceModifier: null' => [
                'listPremiumOnly' => true,
                'priceModifier' => null,
                'expectedListedPlans' => [
                    'Personal' => [
                        'price' => 900,
                        'originalPrice' => 900,
                    ],
                    'Agency' => [
                        'price' => 1900,
                        'originalPrice' => 1900,
                    ],
                    'Business' => [
                        'price' => 5900,
                        'originalPrice' => 5900,
                    ],
                ],
            ],
            'listPremiumOnly: false, priceModifier: 0.4' => [
                'listPremiumOnly' => false,
                'priceModifier' => 0.8,
                'expectedListedPlans' => [
                    'Basic' => [
                        'price' => 0,
                        'originalPrice' => 0,
                    ],
                    'Personal' => [
                        'price' => 720,
                        'originalPrice' => 900,
                    ],
                    'Agency' => [
                        'price' => 1520,
                        'originalPrice' => 1900,
                    ],
                    'Business' => [
                        'price' => 4720,
                        'originalPrice' => 5900,
                    ],
                ],
            ],
            'listPremiumOnly: true, priceModifier: 0.6' => [
                'listPremiumOnly' => true,
                'priceModifier' => 0.6,
                'expectedListedPlans' => [
                    'Personal' => [
                        'price' => 540,
                        'originalPrice' => 900,
                    ],
                    'Agency' => [
                        'price' => 1140,
                        'originalPrice' => 1900,
                    ],
                    'Business' => [
                        'price' => 3540,
                        'originalPrice' => 5900,
                    ],
                ],
            ],
        ];
    }
}
