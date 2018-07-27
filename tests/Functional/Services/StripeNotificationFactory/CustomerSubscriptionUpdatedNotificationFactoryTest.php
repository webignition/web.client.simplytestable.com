<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class CustomerSubscriptionUpdatedNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'customer.subscription.updated';

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
            'customer.subscription.updated; plan_change=1, subscription_status=active' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'active',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                        'plan_change' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'new_plan' => 'agency',
                ],
                'subjectKeyParameterNames' => [
                    'plan_change',
                ],
                'viewNameParameters' => [
                    'plan_change',
                    'subscription_status',
                ],
                'viewParameters' => [
                    'new_plan' => 'agency',
                    'old_plan' => 'personal',
                    'new_amount' => '12.00',
                    'trial_end' => '1 September 2018',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve changed to the agency plan',
                'expectedMessageContains' => [
                    'switched to our agency plan at £12.00 per month',
                    'personal subscription will be pro-rata',
                    'agency subscription will be pro-rata',
                ],
            ],
            'customer.subscription.updated; plan_change=1, subscription_status=trialing' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'trialing',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                        'plan_change' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'new_plan' => 'agency',
                ],
                'subjectKeyParameterNames' => [
                    'plan_change',
                ],
                'viewNameParameters' => [
                    'plan_change',
                    'subscription_status',
                ],
                'viewParameters' => [
                    'new_plan' => 'agency',
                    'old_plan' => 'personal',
                    'new_amount' => '12.00',
                    'trial_end' => '1 September 2018',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve changed to the agency plan',
                'expectedMessageContains' => [
                    'switched to our agency plan',
                    ' free until 1 September 2018',
                ],
            ],
            'customer.subscription.updated; transition=trialing_to_active, has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'transition' => 'trialing_to_active',
                    ]
                ))),
                'subjectValueParameters' => [],
                'subjectKeyParameterNames' => [
                    'transition',
                    'has_card'
                ],
                'viewNameParameters' => [
                    'transition',
                    'has_card'
                ],
                'viewParameters' => [
                    'plan_name' => 'personal',
                    'plan_amount' => '',
                    'account_url' => self::ACCOUNT_URL,
                    'currency_symbol' => '',

                ],
                'expectedSubjectSuffix' => 'Premium trial has ended, you\'ve been dropped down to our free plan',
                'expectedMessageContains' => [
                    'trial of our personal plan has come to an end',
                    self::ACCOUNT_URL,
                ],
            ],
            'customer.subscription.updated; transition=trialing_to_active, has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 1,
                        'plan_name' => 'Personal',
                        'transition' => 'trialing_to_active',
                    ]
                ))),
                'subjectValueParameters' => [],
                'subjectKeyParameterNames' => [
                    'transition',
                    'has_card'
                ],
                'viewNameParameters' => [
                    'transition',
                    'has_card'
                ],
                'viewParameters' => [
                    'plan_name' => 'personal',
                    'plan_amount' => '9.00',
                    'account_url' => self::ACCOUNT_URL,
                    'currency_symbol' => '£',

                ],
                'expectedSubjectSuffix' => 'Premium trial has ended, payment for the next month will be taken soon',
                'expectedMessageContains' => [
                    'trial of our personal plan has come to an end',
                    'will be charged £9.00 per month',
                    self::ACCOUNT_URL,
                ],
            ],
        ];
    }
}
