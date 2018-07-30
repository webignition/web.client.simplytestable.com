<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionDeletedDataProviderTrait
{
    /**
     * @var array
     */
    private $defaultCustomerSubscriptionDeletedEventData = [
        'event' => 'customer.subscription.deleted',
        'plan_name' => 'Personal',
    ];

    /**
     * @return array
     */
    public function customerSubscriptionDeletedDataProvider()
    {
        $listenerMethod = 'onCustomerSubscriptionDeleted';

        return [
            'customer.subscription.deleted; actioned by user during trial, singular trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionDeletedEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 1,
                        'is_during_trial' => 1,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'trial_days_remaining' => 1,
                    'trial_days_remaining_pluralisation' => '',
                    'account_url' => 'http://localhost/account/',
                    'plan_name' => 'personal',
                ],
            ],
            'customer.subscription.deleted; actioned by user during trial, plural trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionDeletedEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 1,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'actioned_by',
                    'is_during_trial',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => 'http://localhost/account/',
                    'plan_name' => 'personal',
                ],
            ],
            'customer.subscription.deleted; actioned by system' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionDeletedEventData,
                    [
                        'actioned_by' => 'system',
                        'trial_days_remaining' => 12,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [
                    'actioned_by',
                ],
                'expectedStripeNotificationViewNameParameters' => [
                    'actioned_by',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => 'http://localhost/account/',
                    'plan_name' => 'personal',
                ],
            ],
        ];
    }
}
