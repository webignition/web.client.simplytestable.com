<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait InvoicePaymentSucceededDataProviderTrait
{
    /**
     * @var array
     */
    private $invoicePaymentSucceededDefaultEventData = [
        'event' => 'invoice.payment_succeeded',
        'user' => 'user@example.com',
    ];

    /**
     * @return array
     */
    public function invoicePaymentSucceededDataProvider()
    {
        return [
            'invoice.payment_succeeded; has_discount=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->invoicePaymentSucceededDefaultEventData,
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
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg paid, thanks!',
                'expectedViewName' => 'Email/Stripe/Event/invoice.payment_succeeded/has_discount=0.txt.twig',
            ],
            'invoice.payment_succeeded; has_discount=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->invoicePaymentSucceededDefaultEventData,
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
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg paid, thanks!',
                'expectedViewName' => 'Email/Stripe/Event/invoice.payment_succeeded/has_discount=1.txt.twig',
            ],
        ];
    }
}
