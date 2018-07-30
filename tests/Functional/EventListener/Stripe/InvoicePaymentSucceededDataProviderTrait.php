<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait InvoicePaymentSucceededDataProviderTrait
{
    /**
     * @var array
     */
    private $defaultInvoicePaymentSucceededEventData = [
        'event' => 'invoice.payment_succeeded',
        'plan_name' => 'Personal',
    ];

    /**
     * @return array
     */
    public function invoicePaymentSucceededDataProvider()
    {
        $listenerMethod = 'onInvoicePaymentSucceeded';

        return [
            'invoice.payment_succeeded; has_discount:0; currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultInvoicePaymentSucceededEventData,
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
                        'total' => '900',
                        'amount_due' => '900',
                        'has_discount' => 0,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#4abfD1nt0ael6N',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_discount',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' => ' * Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00)',
                    'invoice_id' => '#4abfD1nt0ael6N',
                    'subtotal' => 900,
                    'total_line' => '   ====================='."\n".' * Total: £9.00',
                ],
            ],
            'invoice.payment_succeeded; has_discount:0; currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultInvoicePaymentSucceededEventData,
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
                        'total' => '900',
                        'amount_due' => '900',
                        'has_discount' => 0,
                        'currency' => 'usd',
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#4abfD1nt0ael6N',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_discount',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' => ' * Personal plan subscription, 14 August 2014 to 14 September 2014 ($9.00)',
                    'invoice_id' => '#4abfD1nt0ael6N',
                    'subtotal' => 900,
                    'total_line' => '   ====================='."\n".' * Total: $9.00',
                ],
            ],
            'invoice.payment_succeeded; has_discount:0; proration:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultInvoicePaymentSucceededEventData,
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
                        'total' => '900',
                        'amount_due' => '900',
                        'has_discount' => 0,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#4abfD1nt0ael6N',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_discount',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' =>
                        ' * Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00, prorated)',
                    'invoice_id' => '#4abfD1nt0ael6N',
                    'subtotal' => 900,
                    'total_line' => '   ====================='."\n".' * Total: £9.00',
                ],
            ],
            'invoice.payment_succeeded; has_discount:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultInvoicePaymentSucceededEventData,
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
                        'discount' => [
                            'coupon' => 'TMS',
                            'percent_off' => '20',
                            'discount' => 180
                        ],
                        'has_discount' => 1,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#4abfD1nt0ael6N',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_discount',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' => ' * Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00)',
                    'invoice_id' => '#4abfD1nt0ael6N',
                    'subtotal' => 900,
                    'total_line' => '   ====================='."\n".' * Total: £7.20',
                    'discount_line' => ' * 20% off with coupon TMS (-£1.80)',
                ],
            ],
        ];
    }
}
