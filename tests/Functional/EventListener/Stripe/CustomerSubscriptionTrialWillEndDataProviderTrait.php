<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionTrialWillEndDataProviderTrait
{
    private $defaultCustomerSubscriptionTrialWillEndEventData = [
        'event' => 'customer.subscription.trial_will_end',
    ];

    public function customerSubscriptionTrialWillEndDataProvider(): array
    {
        $listenerMethod = 'onCustomerSubscriptionTrialWillEnd';

        return [
            'customer.subscription.trial_will_end; has_card=0, currency=default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionTrialWillEndEventData,
                    [
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                    'payment_details_needed_suffix' => ', payment details needed',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_card',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'plan_amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
            ],
            'customer.subscription.trial_will_end; has_card=0, currency=usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionTrialWillEndEventData,
                    [
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                        'plan_currency' => 'usd',
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                    'payment_details_needed_suffix' => ', payment details needed',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'has_card',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'plan_amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '$',
                ],
            ],
            'customer.subscription.trial_will_end; has_card=1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionTrialWillEndEventData,
                    [
                        'has_card' => 1,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                    'payment_details_needed_suffix' => '',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
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
