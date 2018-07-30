<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionUpdatedDataProviderTrait
{
    /**
     * @var array
     */
    private $customerSubscriptionUpdatedDefaultEventData = [
        'event' => 'customer.subscription.updated',
        'user' => 'user@example.com',
    ];

    /**
     * @return array
     */
    public function customerSubscriptionUpdatedDataProvider()
    {
        return [
            'customer.subscription.updated; plan_change=1, subscription_status=active' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionUpdatedDefaultEventData,
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
                'expectedSubjectSuffix' => 'You\'ve changed to the agency plan',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.updated/'
                    .'plan_change=1-subscription_status=active.txt.twig',
            ],
            'customer.subscription.updated; plan_change=1, subscription_status=trialing' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionUpdatedDefaultEventData,
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
                'expectedSubjectSuffix' => 'You\'ve changed to the agency plan',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.updated/'
                    .'plan_change=1-subscription_status=trialing.txt.twig',
            ],
            'customer.subscription.updated; transition=trialing_to_active, has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionUpdatedDefaultEventData,
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
                'expectedSubjectSuffix' => 'Premium trial has ended, you\'ve been dropped down to our free plan',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.updated/'
                    .'transition=trialing_to_active-has_card=0.txt.twig',
            ],
            'customer.subscription.updated; transition=trialing_to_active, has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionUpdatedDefaultEventData,
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
                'expectedSubjectSuffix' => 'Premium trial has ended, payment for the next month will be taken soon',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.updated/'
                    .'transition=trialing_to_active-has_card=1.txt.twig',
            ],
        ];
    }
}
