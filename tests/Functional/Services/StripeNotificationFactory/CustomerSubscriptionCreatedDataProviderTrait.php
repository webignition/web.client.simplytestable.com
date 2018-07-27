<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

trait CustomerSubscriptionCreatedDataProviderTrait
{
    /**
     * @var array
     */
    private $customerSubscriptionCreatedDefaultEventData = [
        'event' => 'customer.subscription.created',
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    /**
     * @return array
     */
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
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name']),
                    'trial_period_days' => 30,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    '£9.00',
                    'http://localhost/account/',
                ],
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
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name'])
                ],
                'subjectKeyParameterNames' => [],
                'viewNameParameters' => [
                    'status',
                    'has_card'
                ],
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name']),
                    'trial_period_days' => 22,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    'for 22 days',
                    'ending 13 August 2018',
                    'add a credit or debit card to your account',
                ],
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
                'viewParameters' => [
                    'plan_name' => strtolower($this->customerSubscriptionCreatedDefaultEventData['plan_name']),
                    'trial_period_days' => 22,
                    'trial_end' => '13 August 2018',
                    'amount' => '9.00',
                    'account_url' => 'http://localhost/account/',
                    'currency_symbol' => '£',
                ],
                'expectedSubjectSuffix' => 'You\'ve signed up to the personal plan',
                'expectedMessageContains' => [
                    'personal plan',
                    'for 22 days',
                    'ending 13 August 2018',
                ],
            ],
        ];
    }
}
