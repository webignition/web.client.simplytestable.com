<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionUpdatedDataProviderTrait
{
    /**
     * @var array
     */
    private $defaultCustomerSubscriptionUpdatedEventData = [
        'event' => 'customer.subscription.updated',
    ];

    /**
     * @return array
     */
    public function customerSubscriptionUpdatedDataProvider()
    {
        $listenerMethod = 'onCustomerSubscriptionUpdated';

        return [
            'customer.subscription.updated; plan_change=1, subscription_status=active, currency=default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionUpdatedEventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'active',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'new_plan' => 'agency',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'plan_change',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'plan_change',
                    'subscription_status',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'new_plan' => 'agency',
                    'old_plan' => 'personal',
                    'new_amount' => '12.00',
                    'trial_end' => '1 September 2018',
                    'currency_symbol' => '£',
                ],
            ],
            'customer.subscription.updated; plan_change=1, subscription_status=active, currency=usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionUpdatedEventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'active',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                        'currency' => 'usd',
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'new_plan' => 'agency',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'plan_change',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'plan_change',
                    'subscription_status',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'new_plan' => 'agency',
                    'old_plan' => 'personal',
                    'new_amount' => '12.00',
                    'trial_end' => '1 September 2018',
                    'currency_symbol' => '$',
                ],
            ],
            'customer.subscription.updated; transition=trialing_to_active; has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionUpdatedEventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'transition',
                    'has_card',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'transition',
                    'has_card',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'plan_amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
            ],
        ];
    }
}
