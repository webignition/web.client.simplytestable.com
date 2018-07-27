<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class InvoicePaymentSucceededNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'invoice.payment_succeeded';

    /**
     * @var array
     */
    private $defaultEventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider createDataProvider
     *
     * @param StripeEvent $event
     * @param array $subjectValueParameters
     * @param array $subjectKeyParameterNames
     * @param array $viewNameParameters
     * @param array $viewParameters
     * @param string $expectedSubjectSuffix
     * @param array $expectedMessageContains
     */
    public function testCreate(
        StripeEvent $event,
        array $subjectValueParameters,
        array $subjectKeyParameterNames,
        array $viewNameParameters,
        array $viewParameters,
        string $expectedSubjectSuffix,
        array $expectedMessageContains
    ) {
        $notification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            $subjectKeyParameterNames,
            $viewNameParameters,
            $viewParameters
        );

        $this->assertInstanceOf(StripeNotification::class, $notification);

        $this->assertEquals(self::EVENT_USER, $notification->getRecipient());
        $this->assertEquals('[Simply Testable] ' . $expectedSubjectSuffix, $notification->getSubject());

        foreach ($expectedMessageContains as $messageShouldContain) {
            $this->assertContains($messageShouldContain, $notification->getMessage());
        }


        $notification->getMessage();
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            'invoice.payment_succeeded; has_discount=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'lines' => [
                            [
                                'proration' => 0,
                                'plan_name' => 'Personal',
                                'period_start' => 1408031226,
                                'period_end' => 1410709626,
                                'amount' => 900
                            ]
                        ],
                        'invoice_id' => 'in_4abfD1nt0ael6N',
                        'subtotal' => '900',
                        'total' => '720',
                        'amount_due' => '720',
                        'has_discount' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_discount',
                ],
                'viewParameters' => [
                    'plan_name' => 'personal',
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => self::ACCOUNT_URL,
                    'invoice_lines' => ' * Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00)',
                    'subtotal' => (int)900,
                    'total_line' => '£7.20',

                ],
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg paid, thanks!',
                'expectedMessageContains' => [
                    'taken payment for your account subscription',
                    'Invoice #2nL671LyaO5mbg summary',
                    'Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00)',
                ],
            ],
            'invoice.payment_succeeded; has_discount=0, proration=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'lines' => [
                            [
                                'proration' => 1,
                                'plan_name' => 'Personal',
                                'period_start' => 1408031226,
                                'period_end' => 1410709626,
                                'amount' => 900
                            ]
                        ],
                        'invoice_id' => 'in_4abfD1nt0ael6N',
                        'subtotal' => '900',
                        'total' => '720',
                        'amount_due' => '720',
                        'has_discount' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_discount',
                ],
                'viewParameters' => [
                    'plan_name' => 'personal',
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => self::ACCOUNT_URL,
                    'invoice_lines' =>
                        ' * Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00, prorated)',
                    'subtotal' => (int)900,
                    'total_line' => '£7.20',

                ],
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg paid, thanks!',
                'expectedMessageContains' => [
                    'taken payment for your account subscription',
                    'Invoice #2nL671LyaO5mbg summary',
                    'Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00, prorated)',
                ],
            ],
            'invoice.payment_succeeded; has_discount=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'lines' => [
                            [
                                'proration' => 0,
                                'plan_name' => 'Personal',
                                'period_start' => 1408031226,
                                'period_end' => 1410709626,
                                'amount' => 900
                            ]
                        ],
                        'invoice_id' => 'in_2nL671LyaO5mbg',
                        'subtotal' => '900',
                        'total' => '720',
                        'amount_due' => '720',
                        'discount' => [
                            'coupon' => 'TMS',
                            'percent_off' => '20',
                            'discount' => 180
                        ],
                        'has_discount' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_discount',
                ],
                'viewParameters' => [
                    'plan_name' => 'personal',
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => self::ACCOUNT_URL,
                    'invoice_lines' =>
                        ' * Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00, prorated)',
                    'subtotal' => (int)900,
                    'total_line' => 'Total: £7.20',
                    'discount_line' => 'discount line content',

                ],
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg paid, thanks!',
                'expectedMessageContains' => [
                    'taken payment for your account subscription',
                    'Invoice #2nL671LyaO5mbg summary',
                    'Personal plan subscription, 16 August 2018 to 15 September 2018 (£9.00, prorated)',
                    'discount line content',
                    'Total: £7.20',
                ],
            ],
        ];
    }
}
