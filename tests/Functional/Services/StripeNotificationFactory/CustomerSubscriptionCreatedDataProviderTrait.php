<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionCreatedDataProviderTrait
{
    private $customerSubscriptionCreatedDefaultEventData = [
        'event' => 'customer.subscription.created',
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    public function customerSubscriptionCreatedDataProvider()
    {
        return [
            'customer.subscription.created; status:active' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionCreatedDefaultEventData,
                    [
                        'status' => 'active',
                        'amount' => '900',
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedViewName' => 'Email/Stripe/Event/customer.subscription.created/status=active.txt.twig',
            ],
            'customer.subscription.created; status=trialing, has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionCreatedDefaultEventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 0,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                    'has_card'
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.created/status=trialing-has_card=0.txt.twig',
            ],
            'customer.subscription.created; status=trialing, has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionCreatedDefaultEventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 1,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                    'has_card'
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.created/status=trialing-has_card=1.txt.twig',
            ],
        ];
    }
}
