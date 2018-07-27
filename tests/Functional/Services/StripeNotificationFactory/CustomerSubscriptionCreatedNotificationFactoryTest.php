<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class CustomerSubscriptionCreatedNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'customer.subscription.created';

    /**
     * @var array
     */
    private $defaultEventData = [
        'event' => self::EVENT_NAME,
        'plan_name' => 'Personal',
        'user' => self::EVENT_USER,
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
            'customer.subscription.created; status:active' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'status' => 'active',
                        'amount' => '900',
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_period_days' => 30,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => self::ACCOUNT_URL,
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    '£9.00',
                    self::ACCOUNT_URL,
                ],
            ],
            'customer.subscription.created; status=trialing, has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 0,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                    'has_card'
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_period_days' => 22,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => self::ACCOUNT_URL,
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    'for 22 days',
                    'ending 13 August 2018',
                    'add a credit or debit card to your account',
                ],
            ],
            'customer.subscription.created; status=trialing, has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 1,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                    'has_card'
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_period_days' => 22,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => self::ACCOUNT_URL,
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    'for 22 days',
                    'ending 13 August 2018',
                ],
            ],
        ];
    }
}
