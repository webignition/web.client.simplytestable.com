<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait InvoicePaymentFailedDataProviderTrait
{
    private $defaultInvoicePaymentFailedEventData = [
        'event' => 'invoice.payment_failed',
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
    ];

    public function invoicePaymentFailedDataProvider(): array
    {
        $listenerMethod = 'onInvoicePaymentFailed';

        return [
            'invoice.payment_failed; currency=default' => [
                'event' => new StripeEvent(new ParameterBag($this->defaultInvoicePaymentFailedEventData)),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [],
                'expectedStripeNotificationViewParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' => ' * Personal plan subscription, 21 September 2013 to 28 September 2013 (£1.00)',
                ],
            ],
            'invoice.payment_failed; currency=usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultInvoicePaymentFailedEventData,
                    [
                        'currency' => 'usd',
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [],
                'expectedStripeNotificationViewParameters' => [
                    'invoice_id' => '#2nL671LyaO5mbg',
                    'account_url' => 'http://localhost/account/',
                    'invoice_lines' => ' * Personal plan subscription, 21 September 2013 to 28 September 2013 ($1.00)',
                ],
            ],
        ];
    }
}
