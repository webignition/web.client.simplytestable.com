<?php

namespace Tests\AppBundle\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Tests\AppBundle\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\AppBundle\Services\HttpMockHandler;

class OnInvoicePaymentSucceededTest extends AbstractListenerTest
{
    const EVENT_NAME = 'invoice.payment_succeeded';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onInvoicePaymentSucceededDataProvider
     *
     * @param StripeEvent $event
     * @param array $expectedEmailProperties
     *
     * @throws \Twig_Error
     */
    public function testOnInvoicePaymentSucceeded(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onInvoicePaymentSucceeded($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
    }

    /**
     * @return array
     */
    public function onInvoicePaymentSucceededDataProvider()
    {
        return [
            'has_discount:0; currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #4abfD1nt0ael6N paid, thanks!',
                    'TextBody' => [
                        'taken payment for your account subscription',
                        'Invoice #4abfD1nt0ael6N summary',
                        'Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00)',
                    ],
                ],
            ],
            'has_discount:0; currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                        'currency' => 'usd',
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #4abfD1nt0ael6N paid, thanks!',
                    'TextBody' => [
                        'taken payment for your account subscription',
                        'Invoice #4abfD1nt0ael6N summary',
                        'Personal plan subscription, 14 August 2014 to 14 September 2014 ($9.00)',
                    ],
                ],
            ],
            'has_discount:0; proration:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #4abfD1nt0ael6N paid, thanks!',
                    'TextBody' => [
                        'taken payment for your account subscription',
                        'Invoice #4abfD1nt0ael6N summary',
                        'Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00, prorated)',
                    ],
                ],
            ],
            'has_discount:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Invoice #4abfD1nt0ael6N paid, thanks!',
                    'TextBody' => [
                        'taken payment for your account subscription',
                        'Invoice #4abfD1nt0ael6N summary',
                        'Personal plan subscription, 14 August 2014 to 14 September 2014 (£9.00)',
                        '20% off with coupon TMS (-£1.80)',
                        'Total: £7.20',
                    ],
                ],
            ],
        ];
    }
}
