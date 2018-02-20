<?php

namespace Tests\WebClientBundle\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Tests\WebClientBundle\Helper\MockeryArgumentValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

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
     * @param PostmarkMessage $postmarkMessage
     * @throws \Twig_Error
     */
    public function testOnInvoicePaymentFailed(StripeEvent $event, PostmarkMessage $postmarkMessage)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('SimplyTestable\WebClientBundle\Services\Postmark\Sender');

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->listener->onInvoicePaymentFailed($event);

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Invoice #2nL671LyaO5mbg payment failed',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'unable to take payment',
                            'Invoice #2nL671LyaO5mbg summary',
                            'Personal plan subscription, 21 September 2013 to 28 September 2013 (£1.00)',
                        ])),
                    ]
                ),
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Invoice #2nL671LyaO5mbg payment failed',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'unable to take payment',
                            'Invoice #2nL671LyaO5mbg summary',
                            'Personal plan subscription, 21 September 2013 to 28 September 2013 ($1.00)',
                        ])),
                    ]
                ),
            ],
        ];
    }
}
