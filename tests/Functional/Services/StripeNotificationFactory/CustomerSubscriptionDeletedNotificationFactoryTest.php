<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use Symfony\Component\HttpFoundation\ParameterBag;

class CustomerSubscriptionDeletedNotificationFactoryTest extends AbstractStripeNotificationFactoryTest
{
    const EVENT_NAME = 'customer.subscription.deleted';

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
            'customer.subscription.deleted; actioned by user during trial, singular trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 1,
                        'is_during_trial' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewNameParameters' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_days_remaining' => 1,
                    'trial_days_remaining_pluralisation' => '',
                    'account_url' => self::ACCOUNT_URL,
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'remaining 1 day of your trial',
                    self::ACCOUNT_URL,
                ],
            ],
            'customer.subscription.deleted; actioned by user during trial, plural trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewNameParameters' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => self::ACCOUNT_URL,
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'remaining 12 days of your trial',
                    self::ACCOUNT_URL,
                ],
            ],
            'customer.subscription.deleted; actioned by user outside trial' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewNameParameters' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => self::ACCOUNT_URL,
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'drop back to our free basic plan during your premium' . "\n" . 'trial',
                ],
            ],
            'customer.subscription.deleted; actioned by system' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultEventData,
                    [
                        'actioned_by' => 'system',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [
                    'actioned_by',
                ],
                'viewNameParameters' => [
                    'actioned_by',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->defaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => self::ACCOUNT_URL,
                ],
                'expectedSubjectSuffix' =>
                    'Premium subscription to personal cancelled, you\'ve been dropped down to our free plan'
                ,
                'expectedMessageContains' => [
                    self::ACCOUNT_URL,
                ],
            ],
        ];
    }
}
