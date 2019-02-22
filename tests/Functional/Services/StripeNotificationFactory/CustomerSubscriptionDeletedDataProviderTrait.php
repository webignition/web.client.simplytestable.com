<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionDeletedDataProviderTrait
{
    private $customerSubscriptionDeletedDefaultEventData = [
        'event' => 'customer.subscription.deleted',
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    public function customerSubscriptionDeletedDataProvider()
    {
        return [
            'customer.subscription.deleted; actioned by user during trial' => [
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
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.deleted/actioned_by=user-is_during_trial=1.txt.twig',
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
                'expectedSubjectSuffix' => 'Premium subscription to personal cancelled',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.deleted/actioned_by=user-is_during_trial=0.txt.twig',
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
                'expectedSubjectSuffix' =>
                    'Premium subscription to personal cancelled, you\'ve been dropped down to our free plan'
                ,
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.deleted/actioned_by=system.txt.twig',
            ],
        ];
    }
}
