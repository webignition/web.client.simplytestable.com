<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Tests\Services\HttpMockHandler;

class OnInvoicePaymentFailedTest extends AbstractListenerTest
{
    const EVENT_NAME = 'invoice.payment_failed';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onInvoicePaymentFailedDataProvider
     *
     * @param StripeEvent $event
     * @param string[] $expectedEmailProperties
     * @throws \Twig_Error
     */
    public function testOnInvoicePaymentFailed(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onInvoicePaymentFailed($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
    }

    /**
     * @return array
     */
    public function onInvoicePaymentFailedDataProvider()
    {
        return [
            'currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #2nL671LyaO5mbg payment failed',
                    'TextBody' => [
                        'unable to take payment',
                        'Invoice #2nL671LyaO5mbg summary',
                        'Personal plan subscription, 21 September 2013 to 28 September 2013 (£1.00)',
                    ],
                ],
            ],
            'currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                        'currency' => 'usd',
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #2nL671LyaO5mbg payment failed',
                    'TextBody' => [
                        'unable to take payment',
                        'Invoice #2nL671LyaO5mbg summary',
                        'Personal plan subscription, 21 September 2013 to 28 September 2013 ($1.00)',
                    ],
                ],
            ],
        ];
    }
}
