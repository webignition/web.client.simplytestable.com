<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionTrialWillEndDataProviderTrait
{
    private $customerSubscriptionTrialWillEndDefaultEventData = [
        'event' => 'customer.subscription.trial_will_end',
        'user' => 'user@example.com',
        'plan_name' => 'Personal',
    ];

    public function customerSubscriptionTrialWillEndDataProvider()
    {
        return [
            'customer.subscription.trial_will_end; has_card=0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionTrialWillEndDefaultEventData,
                    [
                        'has_card' => 0,
                        'plan_amount' => 900,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionTrialWillEndDefaultEventData['plan_name']),
                    'payment_details_needed_suffix' => ', payment details needed',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_card',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days, payment details needed',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.trial_will_end/has_card=0.txt.twig',
            ],
            'customer.subscription.trial_will_end; has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->customerSubscriptionTrialWillEndDefaultEventData,
                    [
                        'has_card' => 1,
                        'plan_amount' => 900,
                    ]
                ))),
                'subjectValueParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionTrialWillEndDefaultEventData['plan_name']),
                    'payment_details_needed_suffix' => '',
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'has_card',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days',
                'expectedViewName' =>
                    'Email/Stripe/Event/customer.subscription.trial_will_end/has_card=1.txt.twig',
            ],
        ];
    }
}
