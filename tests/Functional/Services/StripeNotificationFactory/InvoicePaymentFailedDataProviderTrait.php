<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait InvoicePaymentFailedDataProviderTrait
{
    /**
     * @var array
     */
    private $invoicePaymentFailedDefaultEventData = [
        'event' => 'invoice.payment_failed',
        'user' => 'user@example.com',
    ];

    /**
     * @return array
     */
    public function invoicePaymentFailedDataProvider()
    {
        return [
            'invoice.payment_failed' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->invoicePaymentFailedDefaultEventData,
                    [
                        'lines' => [
                            [
                                'proration' => 0,
                                'plan_name' => 'Personal',
                                'period_start' => 1379776581,
                                'period_end' => 1380368580,
                                'amount' => 100
                            ]
                        ],
                        'total' => 200,
                        'amount_due' => 300,
                        'invoice_id' => 'in_2nL671LyaO5mbg',
                    ]
                ))),
                'subjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [],
                'viewParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => self::ACCOUNT_URL,
                    'invoice_lines' => ' * Personal plan subscription, 16 August 2018 to 15 September 2018 (£1.00)',
                ],
                'expectedSubjectSuffix' => 'Invoice #2nL671LyaO5mbg payment failed',
                'expectedMessageContains' => [
                    'unable to take payment',
                    'Invoice #2nL671LyaO5mbg summary',
                    'Personal plan subscription, 16 August 2018 to 15 September 2018 (£1.00)',
                ],
            ],
        ];
    }
}
