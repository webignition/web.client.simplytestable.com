<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionDeletedDataProviderTrait
{
    /**
     * @var array
     */
    private $customerSubscriptionDeletedDefaultEventData = [
        'event' => 'customer.subscription.deleted',
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    /**
     * @return array
     */
    public function customerSubscriptionDeletedDataProvider()
    {
        return [
            'customer.subscription.deleted; actioned by user during trial, singular trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionDeletedDefaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 1,
                        'is_during_trial' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name'])
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
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name']),
                    'trial_days_remaining' => 1,
                    'trial_days_remaining_pluralisation' => '',
                    'account_url' => 'http://localhost/account/',
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'remaining 1 day of your trial',
                    'http://localhost/account/',
                ],
            ],
            'customer.subscription.deleted; actioned by user during trial, plural trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionDeletedDefaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 1,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name'])
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
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => 'http://localhost/account/',
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'remaining 12 days of your trial',
                    'http://localhost/account/',
                ],
            ],
            'customer.subscription.deleted; actioned by user outside trial' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionDeletedDefaultEventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name'])
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
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => 'http://localhost/account/',
                ],
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedMessageContains' => [
                    'drop back to our free basic plan during your premium' . "\n" . 'trial',
                ],
            ],
            'customer.subscription.deleted; actioned by system' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionDeletedDefaultEventData,
                    [
                        'actioned_by' => 'system',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 0,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [
                    'actioned_by',
                ],
                'viewNameParameters' => [
                    'actioned_by',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionDeletedDefaultEventData['plan_name']),
                    'trial_days_remaining' => 12,
                    'trial_days_remaining_pluralisation' => 's',
                    'account_url' => 'http://localhost/account/',
                ],
                'expectedSubjectSuffix' =>
                    'Premium subscription to personal cancelled, you\'ve been dropped down to our free plan'
                ,
                'expectedMessageContains' => [
                    'http://localhost/account/',
                ],
            ],
        ];
    }
}
