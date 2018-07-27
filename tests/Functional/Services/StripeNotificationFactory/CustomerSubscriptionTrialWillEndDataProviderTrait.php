<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionTrialWillEndDataProviderTrait
{
    /**
     * @var array
     */
    private $customerSubscriptionTrialWillEndDefaultEventData = [
        'event' => 'customer.subscription.trial_will_end',
        'user' => 'user@example.com',
        'plan_name' => 'Personal',
    ];

    /**
     * @return array
     */
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
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionTrialWillEndDefaultEventData['plan_name']),
                    'account_url' => 'http://localhost/account/',
                    'plan_amount' => '9.00',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days, payment details needed',
                'expectedMessageContains' => [
                    'trial period for your personal account subscription',
                    '£9.00 per month',
                    'add a credit or debit card to your account',
                    'http://localhost/account/',
                ],
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
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionTrialWillEndDefaultEventData['plan_name']),
                    'account_url' => 'http://localhost/account/',
                    'plan_amount' => '9.00',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'Your personal account trial ends in 3 days',
                'expectedMessageContains' => [
                    'trial period for your personal account subscription',
                    '£9.00 per month',
                ],
            ],
        ];
    }
}
