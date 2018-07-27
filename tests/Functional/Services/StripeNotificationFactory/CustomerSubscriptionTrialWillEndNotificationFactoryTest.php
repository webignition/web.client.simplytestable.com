<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class CustomerSubscriptionTrialWillEndNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'customer.subscription.trial_will_end';

    /**
     * @var array
     */
    private $defaultEventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
        'plan_name' => 'Personal',
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
            'customer.subscription.trial_will_end; has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'has_card' => 0,
                        'plan_amount' => 900,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'payment_details_needed_suffix' => ', payment details needed',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_card',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'account_url' => self::ACCOUNT_URL,
                    'plan_amount' => '9.00',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days, payment details needed',
                'expectedMessageContains' => [
                    'trial period for your personal account subscription',
                    '£9.00 per month',
                    'add a credit or debit card to your account',
                    self::ACCOUNT_URL,
                ],
            ],
            'customer.subscription.trial_will_end; has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'has_card' => 1,
                        'plan_amount' => 900,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'payment_details_needed_suffix' => '',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_card',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'account_url' => self::ACCOUNT_URL,
                    'plan_amount' => '9.00',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days',
                'expectedMessageContains' => [
                    'trial period for your personal account subscription',
                    '£9.00 per month',
                ],
            ],
        ];
    }
}
