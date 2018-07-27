<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class InvoicePaymentFailedNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'invoice.payment_failed';

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
            'invoice.payment_failed' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
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
