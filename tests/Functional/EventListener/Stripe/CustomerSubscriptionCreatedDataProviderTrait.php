<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionCreatedDataProviderTrait
{
    /**
     * @var array
     */
    private $defaultCustomerSubscriptionCreatedEventData = [
        'event' => 'customer.subscription.created',
        'plan_name' => 'Personal',
    ];

    /**
     * @return array
     */
    public function customerSubscriptionCreatedDataProvider()
    {
        $listenerMethod = 'onCustomerSubscriptionCreated';

        return [
            'customer.subscription.created; status=active, currency=default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionCreatedEventData,
                    [
                        'status' => 'active',
                        'amount' => '900',
                        'trial_end' => (new \DateTime('2018-07-27'))->format('U'),
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'status',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'trial_period_days' => null,
                    'trial_end' => '27 July 2018',
                    'amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
            ],
            'customer.subscription.created; status=active, currency=usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionCreatedEventData,
                    [
                        'status' => 'active',
                        'amount' => '100',
                        'trial_end' => (new \DateTime('2018-07-27'))->format('U'),
                        'currency' => 'usd',
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'status',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'trial_period_days' => null,
                    'trial_end' => '27 July 2018',
                    'amount' => '1.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '$',
                ],
            ],
            'customer.subscription.created; status=trialing' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->defaultCustomerSubscriptionCreatedEventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'listenerMethod' => $listenerMethod,
                'expectedStripeNotificationSubjectValueParameters' => [
                    'plan_name' => 'personal',
                ],
                'expectedStripeNotificationSubjectKeyParameterNames' => [],
                'expectedStripeNotificationViewNameParameters' => [
                    'status',
                    'has_card',
                ],
                'expectedStripeNotificationViewParameters' => [
                    'plan_name' => 'personal',
                    'trial_period_days' => 22,
                    'trial_end' => '2 January 2018',
                    'amount' => '18.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
            ],
        ];
    }
}
